import serverApi from "$lib/serverApi";

export const load = async ({cookies}) => {
    const api = serverApi(cookies.get('token'))
    return {
        openingHours: await api.get('/settings/opening-hours')
    }
}

export const actions = {
    default: async ({cookies}): Promise<void> => {
        const api = serverApi(cookies.get('token'))
        return await api.put('/settings/opening-hours', {})
    }
}