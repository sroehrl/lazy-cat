import serverApi from "$lib/serverApi";
import { fail, redirect } from "@sveltejs/kit";
import type { Actions } from "./$types";
import { subscriptionPlanSync } from "$lib/utils/paypalApi";

export const actions: Actions = {
    save: async ({ request, cookies, params }) => {
        const api = serverApi(cookies.get('token'));
        const formData = await request.formData();

        const payload = {
            name: formData.get('name'),
            description: formData.get('description'),
            pricePerMonth: Number(formData.get('pricePerMonth')),
            visitationDiscount: Number(formData.get('visitationDiscount')),
            pawsPerMonth: Number(formData.get('pawsPerMonth')),
            weekdays: formData.has('weekdays'),
            weekends: formData.has('weekends'),
            guest: formData.has('guest')
        };

        try {
            const plan = await api.put(`/plans/${params.id}`, payload);
            await subscriptionPlanSync(plan);
        } catch (e: any) {
            return fail(400, { error: e.message, values: payload });
        }

        throw redirect(303, '/administration/plans');
    },
    delete: async ({ cookies, params }) => {
        const api = serverApi(cookies.get('token'));
        try {
            await api.delete(`/plans/${params.id}`);
            // await deleteSubscriptionPlan(params.id);
        } catch (e: any) {
            return fail(400, { error: e.message });
        }

        throw redirect(303, '/administration/plans');
    }
};
