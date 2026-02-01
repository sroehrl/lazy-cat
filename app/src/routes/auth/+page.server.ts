import type { Actions } from './$types';
import serverApi from "$lib/serverApi";
import { fail, redirect } from "@sveltejs/kit";

export const actions = {
    default: async ({ cookies, request }) => {
        const api = serverApi(cookies.get('token'))
        const data = await api.captureFormData(request);
        try {
            const login = await api.post('/auth/authenticate', data)
            cookies.set('token', login.token, { path: '/', sameSite: 'lax', maxAge: 60 * 60 * 24 * 30 });
            cookies.set('isAdmin', login.isAdmin, { path: '/', sameSite: 'lax', maxAge: 60 * 60 * 24 * 30 });

        } catch (e) {
            return fail(400, { email: data.email, error: true })
        }
        redirect(303, data.redirect);
        return { error: false }
    }
} satisfies Actions;