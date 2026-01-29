<script lang="ts">
    import {
        Button,
        Label,
        Input,
        Textarea,
        Toggle,
        Helper,
        Heading,
    } from "flowbite-svelte";
    import { enhance } from "$app/forms";
    import { toastStore } from "$lib/utils/toast";
    import GlasBox from "$lib/components/GlasBox.svelte";
    import { ChevronLeftOutline } from "flowbite-svelte-icons";

    export let form;
    let loading = false;

    $: if (form?.error) {
        toastStore.set({
            open: true,
            message: form.error,
            color: "bg-red-500",
        });
    }
</script>

<div class="max-w-4xl mx-auto p-4">
    <div class="mb-6">
        <Button
            color="alternative"
            outline
            href="/administration/plans"
            class="flex items-center gap-2"
        >
            <ChevronLeftOutline size="sm" /> Back to Plans
        </Button>
    </div>

    <GlasBox>
        <Heading tag="h1" class="mb-6 text-2xl font-bold"
            >Create New Membership Plan</Heading
        >

        <form
            method="POST"
            use:enhance={() => {
                loading = true;
                return async ({ result, update }) => {
                    loading = false;
                    if (result.type === "redirect") {
                        toastStore.set({
                            open: true,
                            message: "Plan created successfully!",
                            color: "bg-green-500",
                        });
                    }
                    update();
                };
            }}
            class="space-y-6"
        >
            <div class="space-y-2">
                <Label for="name">Plan Name*</Label>
                <Input
                    id="name"
                    name="name"
                    placeholder="e.g. Monthly Purr"
                    required
                />
            </div>

            <div class="space-y-2">
                <Label for="description">Description*</Label>
                <Textarea
                    id="description"
                    name="description"
                    placeholder="Describe what's included..."
                    rows="4"
                    required
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <Label for="price">Price per Month ($)*</Label>
                    <Input
                        id="price"
                        name="pricePerMonth"
                        type="number"
                        step="0.01"
                        required
                    />
                </div>
                <div class="space-y-2">
                    <Label for="discount">Visitation Discount (0-1)*</Label>
                    <Input
                        id="discount"
                        name="visitationDiscount"
                        type="number"
                        step="0.01"
                        min="0"
                        max="1"
                        required
                    />
                    <Helper>0.5 = 50% off</Helper>
                </div>
                <div class="space-y-2">
                    <Label for="paws">Paws per Month*</Label>
                    <Input
                        id="paws"
                        name="pawsPerMonth"
                        type="number"
                        required
                    />
                </div>
            </div>

            <div
                class="flex flex-wrap gap-8 py-4 px-2 bg-gray-50 dark:bg-neutral-900 rounded-lg"
            >
                <Toggle name="weekdays" checked>Valid on Weekdays</Toggle>
                <Toggle name="weekends">Valid on Weekends</Toggle>
                <Toggle name="guest">Plus One Guest</Toggle>
            </div>

            <div class="pt-4">
                <Button
                    class="w-full"
                    size="lg"
                    type="submit"
                    disabled={loading}
                >
                    {loading ? "Creating..." : "Create Plan"}
                </Button>
            </div>
        </form>
    </GlasBox>
</div>
