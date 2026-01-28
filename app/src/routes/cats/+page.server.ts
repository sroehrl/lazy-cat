import type { PageServerLoad } from './$types';
import { PUBLIC_API_PATH } from '$env/static/public';

export const load: PageServerLoad = async ({ fetch }) => {
    const response = await fetch(`${PUBLIC_API_PATH}/residents`);
    if (response.ok) {
        const residents = await response.json();
        return {
            residents
        };
    }
    return {
        residents: []
    };
};
