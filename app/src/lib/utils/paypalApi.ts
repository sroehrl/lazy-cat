import { PRIVATE_PAYPAL_CLIENTID, PRIVATE_PAYPAL_SECRET } from "$env/static/private";
import type { SubscriptionPlan } from "$lib/_types/SubscriptionPlan";

const call = async (method: string, url: string, postData: any | null | undefined = null) => {

    let options = {
        method,
        headers: {
            'Authorization': 'bearer ' + PayPalApi.token,
            'Content-Type': 'application/json',
            'Prefer': 'return=representation'
        },
    }
    if (postData) {
        options.body = JSON.stringify(postData)
    }
    const call = await fetch('https://api-m.sandbox.paypal.com/v1' + url, options)
    if (call.status >= 200 && call.status <= 299) {
        return await call.json();
    } else {
        throw Error(call.statusText)
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

// export const deleteSubscriptionPlan = async (productId: number) => {
//     try {
//         const plans = await PayPalApi.get('/billing/plans?page_size=20');
//         if (plans.length) {
//             plans.plans.find(plan => plan.product_id.startsWith(productId));
//             await PayPalApi.post('/billing/plans/' + plans[0].id + '/deactivate');
//         }
//     } catch (e) {
//         return null;
//     }
// }

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
        const call = await fetch('https://api-m.sandbox.paypal.com/v1/oauth2/token?grant_type=client_credentials', {
            method: 'POST',
            headers: {
                ContentType: 'application/x-www-form-urlencoded',
                Accept: 'application/json',
                Authorization: 'Basic ' + btoa(PRIVATE_PAYPAL_CLIENTID + ':' + PRIVATE_PAYPAL_SECRET)
            }
        })
        const tokenData = await call.json();
        console.log({ tokenData })
        PayPalApi.token = tokenData.access_token;
        PayPalApi.expires = now + tokenData.expires_in * 1000;
    },
    get: async (url: string) => call('get', url),
    patch: async (url: string, payload: any) => call('patch', url, payload),
    post: async (url: string, payload: any) => call('post', url, payload),
}
export default PayPalApi