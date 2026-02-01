import { PRIVATE_PAYPAL_SECRET } from "$env/static/private";
import { PUBLIC_PAYPAL_CLIENTID } from "$env/static/public";
import type { SubscriptionPlan } from "$lib/_types/SubscriptionPlan";
import { PUBLIC_PAYPAL_MODE_SANDBOX } from "$env/static/public";
const call = async (method: string, url: string, postData: any | null | undefined = null, version: 'v1' | 'v2' | 'v3' = 'v3') => {

    let options: any = {
        method: method.toUpperCase(),
        headers: {
            'Authorization': 'bearer ' + PayPalApi.token,
            'Content-Type': 'application/json',
            'Prefer': 'return=representation'
        },
    }
    if (postData) {
        options.body = JSON.stringify(postData)
    }
    const call = await fetch(`https://api-m.${PUBLIC_PAYPAL_MODE_SANDBOX ? "sandbox." : ""}paypal.com/${version}${url}`, options)
    if (call.status >= 200 && call.status <= 299) {
        return await call.json();
    } else {
        const error = await call.json();
        console.error('PayPal API Error:', error);
        throw Error(error.message || call.statusText)
    }

}
const productId = (plan: SubscriptionPlan) => "cc-prod-" + plan.id + "-" + plan.createdAt.stamp;

const createProduct = async (plan: SubscriptionPlan) => {
    try {
        return await PayPalApi.post('/catalogs/products', {
            name: plan.name,
            description: plan.description,
            type: 'DIGITAL',
            id: productId(plan),
        });
    } catch (e) {
        console.log(e);

        return null;
    }
}

const createPlan = async (plan: SubscriptionPlan) => {
    try {
        return await PayPalApi.post('/billing/plans', {
            name: plan.name,
            product_id: productId(plan),
            status: 'ACTIVE',
            description: 'Monthly subscription for ' + plan.name,
            billing_cycles: [
                {
                    tenure_type: 'REGULAR',
                    sequence: 1,
                    frequency: {
                        interval_unit: 'MONTH',
                        interval_count: 1
                    },
                    total_cycles: 0,
                    pricing_scheme: {
                        fixed_price: {
                            value: plan.pricePerMonth.toFixed(2),
                            currency_code: 'USD'
                        }
                    }
                }
            ],
            payment_preferences: {
                auto_bill_outstanding: true,
            },
            taxes: {
                inclusive: false,
                percentage: '8.00'
            }
        });
    } catch (e) {
        console.log('Could not create plan')
        console.log(e);

        return null;
    }
}

export const subscriptionPlanSync = async (plan: SubscriptionPlan) => {
    await PayPalApi.auth();
    // check if product exitst
    let product: any;
    let payPalPlan: any;
    try {
        product = await PayPalApi.get('/catalogs/products/' + productId(plan));
    } catch (e) {

        product = await createProduct(plan);
    }
    console.log({ product });


    if (!product) {
        return;
    }

    try {
        const plans = await PayPalApi.get('/billing/plans?product_id=' + productId(plan));
        if (plans.plans.length < 1) {
            throw Error('No plans found');
        }

        payPalPlan = plans.plans[0];
    } catch (e) {
        payPalPlan = await createPlan(plan);
    }
    console.log({ payPalPlan });


    if (!payPalPlan) {
        return;
    }
    // update pricing?
    if (payPalPlan.billing_cycles[0].pricing_scheme.fixed_price.value !== plan.pricePerMonth.toFixed(2)) {
        await PayPalApi.post('/billing/plans/' + payPalPlan.id + '/update-pricing-schemes', {
            pricing_schemes: [{
                billing_cycle_sequence: 1,
                pricing_scheme: {
                    fixed_price: {
                        value: plan.pricePerMonth.toFixed(2),
                        currency_code: 'USD'
                    }
                }
            }]
        })
    }
}

const PayPalApi = {
    token: '',
    expires: 0,
    auth: async () => {
        const now = Date.now();
        if (now < PayPalApi.expires) {
            return;
        }
        const call = await fetch(`https://api-m.${PUBLIC_PAYPAL_MODE_SANDBOX ? "sandbox." : ""}paypal.com/v1/oauth2/token?grant_type=client_credentials`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                Accept: 'application/json',
                Authorization: 'Basic ' + btoa(PUBLIC_PAYPAL_CLIENTID + ':' + PRIVATE_PAYPAL_SECRET)
            }
        })
        const tokenData = await call.json();
        PayPalApi.token = tokenData.access_token;
        PayPalApi.expires = now + tokenData.expires_in * 1000;
    },
    get: async (url: string, version: 'v1' | 'v2' | 'v3' = 'v1') => call('get', url, null, version),
    patch: async (url: string, payload: any, version: 'v1' | 'v2' | 'v3' = 'v1') => call('patch', url, payload, version),
    post: async (url: string, payload: any, version: 'v1' | 'v2' | 'v3' = 'v1') => call('post', url, payload, version),

    // Vaulting V3
    createSetupToken: async (paymentSource: 'CARD' | 'PAYPAL' | 'VENMO' | null = null, customerId: string) => {
        let payload: any = {
            payment_source: {}
        };
        if (paymentSource) {
            payload.payment_source[paymentSource.toLowerCase()] = {};
        } else {
            payload.payment_source.paypal = {
                description: 'Lazy Cat Cafe wants to save your paypal account for future payments. You can remove it anytime in your account settings.',
                usage_pattern: 'IMMEDIATE',
                usage_type: 'MERCHANT',
                customer_type: 'CONSUMER'
            };

        }
        payload.customer = {
            merchant_customer_id: customerId
        };
        return PayPalApi.post('/vault/setup-tokens', payload, 'v3');
    },
    createPaymentToken: async (setupToken: string) => {
        return PayPalApi.post('/vault/payment-tokens', {
            payment_source: {
                token: {
                    id: setupToken,
                    type: 'SETUP_TOKEN'
                }
            }
        }, 'v3');
    }
}
export default PayPalApi