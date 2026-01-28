import {Actions, redirect} from "@sveltejs/kit";

export const actions: Actions = {
    signOut: async ({cookies, url}) => {
        console.log('hit')
        cookies.delete('token', {path: '/'})
        cookies.delete('isAdmin', {path: '/'})
        redirect(303, '/auth')
    }

}