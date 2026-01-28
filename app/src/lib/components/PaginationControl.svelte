<script lang="ts">
    import {Button, Label, Select} from "flowbite-svelte";

    export let paginationResult: {page: number, pageSize: number, total: number, collection: any[]} = {page: 1, pageSize: 20, collection: []}
</script>

<div class="flex gap-3 justify-between">
    <Label class="space-y-2">
        <span>Results per page</span>
        <Select bind:value={paginationResult.pageSize}>
            <option value={20}>20</option>
            <option value={50}>50</option>
            <option value={100}>100</option>
        </Select>
    </Label>
    <div class="grid items-center place-items-center">
        <span class="dark:text-white">Total: {paginationResult.total}</span>
        <div>
            <Button disabled={paginationResult.page === 1} on:click={() => paginationResult.page--}>Previous</Button>
            <Button disabled={paginationResult.page === Math.ceil(paginationResult.collection.length / paginationResult.pageSize)} on:click={() => paginationResult.page++}>Next</Button>
        </div>
    </div>
    <Label class="space-y-2">
        <span>Page</span>
        <Select bind:value={paginationResult.page}>
            {#each Array(Math.ceil(paginationResult.collection.length / paginationResult.pageSize)) as _, i}
                <option value={i + 1}>{i + 1}</option>
            {/each}
        </Select>
    </Label>
</div>