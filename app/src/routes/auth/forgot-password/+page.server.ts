import type { Actions } from './$types';
import serverApi from "$lib/serverApi";
import { fail, redirect } from "@sveltejs/kit";

export const load = async ({ url }) => {
    return {
        code: url.searchParams.get('code'),
        email: url.searchParams.get('email')
    }
}

export const actions = {
    default: async ({ cookies, request }) => {
        const api = serverApi(cookies.get('token'))
        const data = await api.captureFormData(request);
        try {
            const login = await api.post('/auth/reset-password', data)
            if (data.token) {
                cookies.set('token', login.token, { path: '/', sameSite: 'strict', maxAge: 60 * 60 * 24 * 30 });
                cookies.set('isAdmin', login.isAdmin, { path: '/', sameSite: 'strict', maxAge: 60 * 60 * 24 * 30 });
                return redirect(303, '/');
            } else {
                return { success: true }
            }

        } catch (e: any) {
            console.log({ data })
            return fail(400, { email: data.email, error: e.message })
        }
    }
} satisfies Actions;
