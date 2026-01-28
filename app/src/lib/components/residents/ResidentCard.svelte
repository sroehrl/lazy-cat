<script lang="ts">
    import { Card, Button, Badge } from "flowbite-svelte";
    import type { Resident } from "$lib/_types/Resident";
    import { createEventDispatcher } from "svelte";
    import dayjs from "dayjs";
    import relativeTime from "dayjs/plugin/relativeTime";

    dayjs.extend(relativeTime);

    export let resident: Resident;
    export let readOnly: boolean = false;

    const dispatch = createEventDispatcher();

    function getStatusColor(status: string) {
        switch (status) {
            case "available":
                return "green";
            case "pending":
                return "yellow";
            case "adopted":
                return "red";
            default:
                return "none";
        }
    }
</script>

<Card padding="none" class="overflow-hidden">
    {#if resident.image}
        <img
            src={resident.image}
            alt={resident.name}
            class="h-48 w-full object-cover"
        />
    {:else}
        <div
            class="h-48 w-full bg-gray-200 flex items-center justify-center dark:bg-gray-700"
        >
            <span class="text-gray-400">No Image</span>
        </div>
    {/if}
    <div class="p-4">
        <div class="flex justify-between items-start mb-2">
            <h5
                class="text-xl font-bold tracking-tight text-gray-900 dark:text-white"
            >
                {resident.name}
            </h5>
            <Badge color={getStatusColor(resident.status)}
                >{resident.status}</Badge
            >
        </div>
        <p
            class="mb-3 font-normal text-gray-700 dark:text-gray-400 line-clamp-2"
        >
            {resident.description}
        </p>
        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            <p><strong>Gender:</strong> {resident.gender}</p>
            {#if resident.breed}<p>
                    <strong>Breed:</strong>
                    {resident.breed}
                </p>{/if}
            {#if resident.color}<p>
                    <strong>Color:</strong>
                    {resident.color}
                </p>{/if}
            {#if resident.bornAt}<p>
                    <strong>Born:</strong>
                    {dayjs(resident.bornAt.value).format("MM/DD/YYYY")}
                    <span class="ml-2"
                        >({dayjs(resident.bornAt.value).fromNow(true)} old)</span
                    >
                </p>{/if}
        </div>
        {#if !readOnly}
            <Button size="sm" on:click={() => dispatch("edit", resident)}
                >Edit</Button
            >
        {/if}
    </div>
</Card>
