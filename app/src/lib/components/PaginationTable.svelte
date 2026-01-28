<script lang="ts">
    import { Table, TableBody, TableBodyCell, TableBodyRow, TableHead, TableHeadCell } from 'flowbite-svelte';

    export let data: any[] = [];

    export let renderValue = (key: string, value: any) => {
        return value ? value : 'N/A';
    }

    $: header = data.length > 0 ? Object.keys(data[0]) : [];


</script>
<Table shadow>
    <TableHead>
        {#each header as headerItem}
            <TableHeadCell>{headerItem.toUpperCase()}</TableHeadCell>
        {/each}

    </TableHead>
    <TableBody tableBodyClass="divide-y">
        {#each data as item}
            <TableBodyRow>
            {#each header as key}
                    <TableBodyCell>
                        {#if renderValue(key, item[key]) instanceof Object}
                            <svelte:component this={renderValue(key, item[key]).component} {...renderValue(key, item[key]).props}/>
                        {:else}
                            {@html renderValue(key, item[key])}
                        {/if}
                    </TableBodyCell>
            {/each}
            </TableBodyRow>
        {/each}
    </TableBody>
</Table>