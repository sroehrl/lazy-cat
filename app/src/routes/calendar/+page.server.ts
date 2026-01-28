import serverApi from "$lib/serverApi";

export const load = async ({cookies}) => {
    const api = serverApi(cookies.get('token'))
    return {
        hours: await api.get('/settings/opening-hours'),
        reserved: await api.get('/lounge/occupancy')
    }

}