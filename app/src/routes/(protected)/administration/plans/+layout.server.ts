import serverApi from "$lib/serverApi";
import paypalApi from "$lib/utils/paypalApi";

export const load = async ({ cookies, params }) => {
    const api = serverApi(cookies)
    const allPlans = await api.get('/plans?pageSize=100&orderBy=pricePerMonth')
    await paypalApi.auth()
    return {
        params,
        plans: allPlans.collection,
        paypalPlans: await paypalApi.get('/billing/plans')
    }
}