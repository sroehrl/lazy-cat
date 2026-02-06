import { PRIVATE_WHOP_API_KEY } from "$env/static/private";
import { PUBLIC_WHOP_BIZ_KEY } from "$env/static/public";
import type { Product } from "$lib/_types/Product";
import Whop from "@whop/sdk";
const client = new Whop({
    apiKey: PRIVATE_WHOP_API_KEY,
});


export const whopProducts = async () => {
    const products = await client.products.list({ company_id: PUBLIC_WHOP_BIZ_KEY });
    return products;
}

export const whopPlans = async () => {
    const plans = await client.plans.list({ company_id: PUBLIC_WHOP_BIZ_KEY });
    return plans;
}

export const whopCreatePlan = async (product: Product) => {
    // create Product
    const whopProduct = await client.products.create({
        company_id: PUBLIC_WHOP_BIZ_KEY,
        title: product.name,
        description: product.description,
        business_type: 'community',
        custom_statement_descriptor: 'Lazy Cat Cafe Membership',
        global_affiliate_percentage: 8,
        headline: 'Lazy Cat Cafe Membership',
        industry_type: 'coffee_shop',
        plan_options: {
            base_currency: 'usd',
            billing_period: 'monthly',

        }


    });
    const plan = await client.plans.sync({ company_id: PUBLIC_WHOP_BIZ_KEY, name });
    return plan;
}