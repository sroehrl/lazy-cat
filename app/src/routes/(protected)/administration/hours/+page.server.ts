import serverApi from "$lib/serverApi";

export const load = async ({cookies}) : Promise<{hours: {openingHoursCafe: any[], openingHoursLounge: any[], blockoutDays: any[], customHoursCafe: any[], customHoursLounge: any[]}}> => {
    const api = serverApi(cookies.get('token'))
    return {
        hours: await api.get('/settings/opening-hours')
    }
}

export const actions = {
    default: async ({cookies}): Promise<void> => {
        const api = serverApi(cookies.get('token'))
        return await api.put('/settings/opening-hours', {})
    }
}