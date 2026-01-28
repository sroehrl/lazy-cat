// @ts-nocheck
import {Actions, redirect} from "@sveltejs/kit";

export const actions = {
    signOut: async ({cookies, url}: import('./$types').RequestEvent) => {
        console.log('hit')
        cookies.delete('token', {path: '/'})
        cookies.delete('isAdmin', {path: '/'})
        redirect(303, '/auth')
    }

};null as any as Actions;