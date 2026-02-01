<script lang="ts">
    import { onMount } from "svelte";
    import { Button, Card, Heading, P, Badge, Hr } from "flowbite-svelte";
    import {
        TrashBinOutline,
        CreditCardOutline,
        CashSolid,
    } from "flowbite-svelte-icons";
    import { enhance } from "$app/forms";
    import { toast, toastStore } from "$lib/utils/toast";
    import { PUBLIC_PAYPAL_MODE_SANDBOX } from "$env/static/public";

    export let data;
    export let form;

    let loading = false;

    $: if (form?.error) {
        toastStore.set({
            open: true,
            message: form.error,
            color: "bg-red-500",
        });
    }

    $: if (form?.success && form?.paymentMethod) {
        toastStore.set({
            open: true,
            message: "Payment method saved successfully!",
            color: "bg-green-500",
        });
    }

    onMount(() => {
        console.log({ user: data.user });
        const script = document.createElement("script");
        script.src = `https://www.${PUBLIC_PAYPAL_MODE_SANDBOX ? "sandbox." : ""}paypal.com/sdk/js?client-id=${data.paypalClientId}&components=buttons&vault=true`;
        script.onload = () => {
            // @ts-ignore
            if (window.paypal) {
                // @ts-ignore
                window.paypal
                    .Buttons({
                        style: {
                            layout: "vertical",
                            color: "gold",
                            shape: "rect",
                            label: "paypal",
                        },
                        createVaultSetupToken: async () => {
                            const formData = new FormData();
                            formData.append("customerId", data.user.email);
                            const response = await fetch("?/createSetupToken", {
                                method: "POST",
                                body: formData,
                            });
                            const res = await response.json();
                            const result = JSON.parse(res.data);

                            console.log(result[2]);

                            // SvelteKit action result parsing varies, but usually it's [type, payload]
                            // Depending on the version, it might be { type: 'success', data: { setupToken: { id: ... } } }
                            // Based on the previous log, it seems to be an array [type, payload]
                            return result[2];
                        },
                        onApprove: async (approvalData: {
                            vaultSetupToken: string;
                        }) => {
                            const setupTokenId = approvalData.vaultSetupToken;

                            const formData = new FormData();
                            formData.append("setupTokenId", setupTokenId);

                            await fetch("?/savePaymentMethod", {
                                method: "POST",
                                body: formData,
                            });

                            window.location.reload();
                        },
                        onError: (err: any) => {
                            console.error("PayPal Error:", err);
                            toast.error("PayPal initialization failed");
                        },
                    })
                    .render("#paypal-button-container");
            }
        };
        document.head.appendChild(script);
    });
</script>

<div class="max-w-4xl mx-auto p-4">
    <Heading tag="h1" class="mb-4">Payment Methods</Heading>
    <P class="mb-8 text-gray-500"
        >Manage your saved payment methods for faster checkout and
        subscriptions.</P
    >

    <div class="grid gap-6 md:grid-cols-2">
        <section>
            <Heading tag="h2" class="text-xl font-bold mb-4"
                >Saved Methods</Heading
            >
            <ul class="space-y-4">
                {#each data.paymentMethods as method}
                    <li
                        class="bg-white dark:bg-neutral-800 p-4 rounded-lg border flex justify-between items-center shadow-sm"
                    >
                        <div class="flex items-center gap-3">
                            {#if method.tokenType === "CARD"}
                                <CreditCardOutline
                                    class="w-6 h-6 text-primary-600"
                                />
                            {:else}
                                <CashSolid class="w-6 h-6 text-blue-600" />
                            {/if}
                            <div>
                                <P class="font-bold">{method.identifier}</P>
                                <Badge
                                    color={method.status === "ACTIVE"
                                        ? "green"
                                        : "red"}>{method.status}</Badge
                                >
                            </div>
                        </div>
                        <form
                            method="POST"
                            action="?/delete"
                            use:enhance={() => {
                                loading = true;
                                return async ({ result }) => {
                                    loading = false;
                                    if (result.type === "success") {
                                        toastStore.set({
                                            open: true,
                                            message: "Deleted",
                                            color: "bg-green-500",
                                        });
                                    }
                                };
                            }}
                        >
                            <input type="hidden" name="id" value={method.id} />
                            <Button
                                outline
                                color="red"
                                size="sm"
                                type="submit"
                                disabled={loading}
                            >
                                <TrashBinOutline class="w-4 h-4" />
                            </Button>
                        </form>
                    </li>
                {:else}
                    <P class="text-gray-500 italic">No payment methods saved.</P
                    >
                {/each}
            </ul>
        </section>

        <section>
            <Card
                padding="lg"
                class="bg-gray-50 dark:bg-neutral-900 border-dashed border-2"
            >
                <Heading tag="h2" class="text-xl font-bold mb-4"
                    >Add New Method</Heading
                >
                <P class="mb-6 text-gray-500 text-sm"
                    >Vault your payment method securely using PayPal. We do not
                    store your full card details.</P
                >

                <div id="paypal-button-container"></div>

                <Hr class="my-6" />
                <P class="text-xs text-gray-400 text-center"
                    >Supports Card, PayPal, and Venmo</P
                >
            </Card>
        </section>
    </div>
</div>
