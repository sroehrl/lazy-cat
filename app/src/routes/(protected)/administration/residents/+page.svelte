<script lang="ts">
    import { Button, Heading } from "flowbite-svelte";
    import type { Resident } from "$lib/_types/Resident";
    import ResidentCard from "$lib/components/residents/ResidentCard.svelte";
    import ResidentModal from "$lib/components/residents/ResidentModal.svelte";
    import { post, put } from "$lib/api";
    import { invalidateAll } from "$app/navigation";
    import type { PageData } from "./$types";

    export let data: PageData;

    $: residents = data.residents as Resident[];

    let modalOpen = false;
    let selectedResident: Partial<Resident> = {};

    function openCreate() {
        selectedResident = {
            status: "available",
            gender: "female",
        };
        modalOpen = true;
    }

    function openEdit(event: CustomEvent<Resident>) {
        selectedResident = { ...event.detail };
        modalOpen = true;
    }

    async function handleSave(event: CustomEvent<Partial<Resident>>) {
        const payload = event.detail;
        try {
            if (payload.id) {
                await put(`/resident/${payload.id}`, payload);
            } else {
                await post("/resident", payload);
            }
            modalOpen = false;
            await invalidateAll();
        } catch (e: any) {
            alert(`Error: ${e.message || "Failed to save resident"}`);
        }
    }
</script>

<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <Heading tag="h2">Resident Administration</Heading>
        <Button on:click={openCreate}>Add Resident</Button>
    </div>

    <div
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
    >
        {#each residents as resident (resident.id)}
            <ResidentCard {resident} on:edit={openEdit} />
        {:else}
            <div class="col-span-full text-center py-12 text-gray-500">
                No residents found. Add your first one!
            </div>
        {/each}
    </div>
</div>

<ResidentModal
    bind:open={modalOpen}
    resident={selectedResident}
    on:save={handleSave}
/>
