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
    import { put, retire } from "$lib/api";
    import { goto } from "$app/navigation";
    import { toastStore } from "$lib/utils/toast";
    import GlasBox from "$lib/components/GlasBox.svelte";
    import { ChevronLeftOutline, TrashBinOutline } from "flowbite-svelte-icons";

    export let data;
    let plan = data.plans.find((plan: any) => plan.id === Number(data.id));

    let name = plan?.name || "";
    let description = plan?.description || "";
    let pricePerMonth = plan?.pricePerMonth || 0;
    let visitationDiscount = plan?.visitationDiscount || 0;
    let pawsPerMonth = plan?.pawsPerMonth || 1;
    let weekdays = !!plan?.weekdays;
    let weekends = !!plan?.weekends;
    let guest = !!plan?.guest;

    let loading = false;

    async function save() {
        loading = true;
        try {
            await put(`/plans/${plan.id}`, {
                name,
                description,
                pricePerMonth,
                visitationDiscount,
                pawsPerMonth,
                weekdays,
                weekends,
                guest,
            });
            toastStore.set({
                open: true,
                message: "Plan updated successfully!",
                color: "bg-green-500",
            });
            goto("/administration/plans");
        } catch (e: any) {
            toastStore.set({
                open: true,
                message: e.message || "Failed to update plan",
                color: "bg-red-500",
            });
        } finally {
            loading = false;
        }
    }

    async function deletePlan() {
        if (!confirm("Are you sure you want to delete this plan?")) return;
        loading = true;
        try {
            await retire(`/plans/${plan.id}`);
            toastStore.set({
                open: true,
                message: "Plan deleted successfully!",
                color: "bg-green-500",
            });
            goto("/administration/plans");
        } catch (e: any) {
            toastStore.set({
                open: true,
                message: e.message || "Failed to delete plan",
                color: "bg-red-500",
            });
        } finally {
            loading = false;
        }
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
        <Button
            color="red"
            on:click={deletePlan}
            disabled={loading}
            class="flex items-center gap-2"
        >
            <TrashBinOutline size="sm" /> Delete Plan
        </Button>
    </div>

    <GlasBox>
        <Heading tag="h1" class="mb-6 text-2xl font-bold"
            >Edit Plan: {plan?.name}</Heading
        >

        <div class="space-y-6">
            <div class="space-y-2">
                <Label for="name">Plan Name*</Label>
                <Input
                    id="name"
                    bind:value={name}
                    placeholder="e.g. Monthly Purr"
                    required
                />
            </div>

            <div class="space-y-2">
                <Label for="description">Description*</Label>
                <Textarea
                    id="description"
                    bind:value={description}
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
                        type="number"
                        step="0.01"
                        bind:value={pricePerMonth}
                        required
                    />
                </div>
                <div class="space-y-2">
                    <Label for="discount">Visitation Discount (0-1)*</Label>
                    <Input
                        id="discount"
                        type="number"
                        step="0.01"
                        min="0"
                        max="1"
                        bind:value={visitationDiscount}
                        required
                    />
                    <Helper>0.5 = 50% off</Helper>
                </div>
                <div class="space-y-2">
                    <Label for="paws">Paws per Month*</Label>
                    <Input
                        id="paws"
                        type="number"
                        bind:value={pawsPerMonth}
                        required
                    />
                </div>
            </div>

            <div
                class="flex flex-wrap gap-8 py-4 px-2 bg-gray-50 dark:bg-neutral-900 rounded-lg"
            >
                <Toggle bind:checked={weekdays}>Valid on Weekdays</Toggle>
                <Toggle bind:checked={weekends}>Valid on Weekends</Toggle>
                <Toggle bind:checked={guest}>Plus One Guest</Toggle>
            </div>

            <div class="pt-4">
                <Button
                    class="w-full"
                    size="lg"
                    on:click={save}
                    disabled={loading}
                >
                    {loading ? "Saving..." : "Save Changes"}
                </Button>
            </div>
        </div>
    </GlasBox>
</div>
