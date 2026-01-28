import serverApi from "$lib/serverApi";
import paypalApi from "$lib/utils/paypalApi";

export const load = async ({cookies}) => {
    const api = serverApi(cookies)
    const allPlans = await api.get('/plans?pageSize=100&orderBy=pricePerMonth')
    await paypalApi.auth()
    return {
        plans: allPlans.collection,
        paypalPlans: await paypalApi.get('/billing/plans')
    }
}