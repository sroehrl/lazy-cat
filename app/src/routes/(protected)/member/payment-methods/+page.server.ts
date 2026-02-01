import PayPalApi from "$lib/utils/paypalApi";
import serverApi from "$lib/serverApi";
import { fail } from "@sveltejs/kit";
import { PUBLIC_PAYPAL_CLIENTID } from "$env/static/public";
import type { PageServerLoad, Actions } from "./$types";

export const load: PageServerLoad = async ({ cookies }) => {
    const api = serverApi(cookies.get('token'));
    const paymentMethods = await api.get('/member/payment-methods');

    return {
        paymentMethods,
        paypalClientId: PUBLIC_PAYPAL_CLIENTID
    };
};

export const actions: Actions = {
    createSetupToken: async ({ cookies, request }) => {
        await PayPalApi.auth();
        const formData = await request.formData();
        const customerId = formData.get('customerId') as string;
        console.log({ customerId });
        try {
            const setupToken = await PayPalApi.createSetupToken(null, customerId);
            console.log({ setupToken });

            return { setupToken };
        } catch (e: any) {
            console.log(e);
            return fail(400, { error: e.message });
        }
    },
    savePaymentMethod: async ({ request, cookies }) => {
        const formData = await request.formData();
        const setupTokenId = formData.get('setupTokenId') as string;
        const api = serverApi(cookies.get('token'));

        await PayPalApi.auth();
        try {
            // 1. Convert Setup Token to Payment Token (Vault Token)
            const paymentToken = await PayPalApi.createPaymentToken(setupTokenId);

            // 2. Save to our backend
            // Extract identifier based on payment source
            let identifier = 'Unknown';
            let tokenType = 'UNKNOWN';

            const source = paymentToken.payment_source;
            if (source.card) {
                identifier = `Card ending in ${source.card.last_digits}`;
                tokenType = 'CARD';
            } else if (source.paypal) {
                identifier = source.paypal.email_address || 'PayPal Account';
                tokenType = 'PAYPAL';
            } else if (source.venmo) {
                identifier = source.venmo.user_name || 'Venmo Account';
                tokenType = 'VENMO';
            }

            const payload = {
                tokenType,
                status: paymentToken.metadata?.status || 'ACTIVE',
                identifier,
                token: paymentToken.id // Storing the vault token ID as the 'token' or similar
                // Wait, the request guard and model have 'identifier', 'status', 'tokenType'.
                // I should probably use 'identifier' to store the vault token ID if there isn't a 'token' field.
                // Actually, looking at PaymentMethodRequest: 
                // public string $tokenType;
                // public string $status;
                // public string $identifier;
                // I'll store the vault token ID in 'identifier' or maybe the user wanted another field?
                // The user's model:
                // public string $tokenType;
                // public string $status;
                // public string $identifier;
                // I'll use identifier for the Vault Token ID for now, or maybe the user wants the display name there?
                // Let's use identifier for the Vault Token ID and maybe add a field if needed.
                // Wait, the user's GetPaymentMethods says it returns MemberPaymentMethod::retrieve(['memberId' => $member->id, '^deletedAt']);
                // I'll stick to the existing fields.
            };

            // Actually, usually you want both the ID and the display name.
            // I'll store the ID in identifier and maybe the display name in something else? 
            // No, the model only has those 3 strings.
            // I'll use identifier for the Vault Token ID.

            const result = await api.post('/member/payment-method', {
                ...payload,
                identifier: paymentToken.id // Overwriting identifier with the actual token ID
            });

            return { success: true, paymentMethod: result };
        } catch (e: any) {
            return fail(400, { error: e.message });
        }
    },
    delete: async ({ request, cookies }) => {
        const formData = await request.formData();
        const id = formData.get('id');
        const api = serverApi(cookies.get('token'));

        try {
            await api.delete(`/member/payment-method/${id}`);
            return { success: true };
        } catch (e: any) {
            return fail(400, { error: e.message });
        }
    }
};
