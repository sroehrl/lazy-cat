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
    import { ChevronLeftOutline, TrashBinOutline } from "flowbite-svelte-icons";

    export let data;
    export let form;
    let plan = data.plans.find((plan: any) => plan.id === Number(data.id));

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
    <div class="flex justify-between items-center mb-6">
        <Button
            color="alternative"
            outline
            href="/administration/plans"
            class="flex items-center gap-2"
        >
            <ChevronLeftOutline size="sm" /> Back to Plans
        </Button>
        <form
            method="POST"
            action="?/delete"
            use:enhance={({ cancel }) => {
                if (!confirm("Are you sure you want to delete this plan?"))
                    return cancel();
                loading = true;
                return async ({ result, update }) => {
                    loading = false;
                    if (result.type === "redirect") {
                        toastStore.set({
                            open: true,
                            message: "Plan deleted successfully!",
                            color: "bg-green-500",
                        });
                    }
                    update();
                };
            }}
        >
            <Button
                color="red"
                type="submit"
                disabled={loading}
                class="flex items-center gap-2"
            >
                <TrashBinOutline size="sm" /> Delete Plan
            </Button>
        </form>
    </div>

    <GlasBox>
        <Heading tag="h1" class="mb-6 text-2xl font-bold"
            >Edit Plan: {plan?.name}</Heading
        >

        <form
            method="POST"
            action="?/save"
            use:enhance={() => {
                loading = true;
                return async ({ result, update }) => {
                    loading = false;
                    if (result.type === "redirect") {
                        toastStore.set({
                            open: true,
                            message: "Plan updated successfully!",
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
                    value={plan?.name || ""}
                    placeholder="e.g. Monthly Purr"
                    required
                />
            </div>

            <div class="space-y-2">
                <Label for="description">Description*</Label>
                <Textarea
                    id="description"
                    name="description"
                    value={plan?.description || ""}
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
                        value={plan?.pricePerMonth || 0}
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
                        value={plan?.visitationDiscount || 0}
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
                        value={plan?.pawsPerMonth || 1}
                        required
                    />
                </div>
            </div>

            <div
                class="flex flex-wrap gap-8 py-4 px-2 bg-gray-50 dark:bg-neutral-900 rounded-lg"
            >
                <Toggle name="weekdays" checked={!!plan?.weekdays}
                    >Valid on Weekdays</Toggle
                >
                <Toggle name="weekends" checked={!!plan?.weekends}
                    >Valid on Weekends</Toggle
                >
                <Toggle name="guest" checked={!!plan?.guest}
                    >Plus One Guest</Toggle
                >
            </div>

            <div class="pt-4">
                <Button
                    class="w-full"
                    size="lg"
                    type="submit"
                    disabled={loading}
                >
                    {loading ? "Saving..." : "Save Changes"}
                </Button>
            </div>
        </form>
    </GlasBox>
</div>
