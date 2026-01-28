import serverApi from "$lib/serverApi";
import type {CollectionType} from "$lib/_types/CollectionType";

export const load = async ({cookies, url}): Promise<{members:CollectionType}> => {
    const api = serverApi(cookies.get('token'))
    let sortBy = url.searchParams.get('sortBy') || 'lastName'
    const members = await api.get('/members?sortBy=' + sortBy)
    return {
        members
    }
}