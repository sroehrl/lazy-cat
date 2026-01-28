<script>
    import {Modal, Table, TableBody, TableBodyCell, TableBodyRow, TableHead, TableHeadCell} from "flowbite-svelte";
export let open = false;
</script>

<Modal bind:open title="Paws Explanation">
    <p>Paws are accumulating in your account and can be used as an in-store currency.</p>
    <p>You can earn paws by qualifying purchases through our website, or via membership plans.</p>
    <p>To give you a better understanding of how we work with paws, please see the table below.</p>

    <Table shadow>
    <TableHead>
        <TableHeadCell>Product name</TableHeadCell>
        <TableHeadCell>Paws needed</TableHeadCell>
    </TableHead>
    <TableBody tableBodyClass="divide-y">

        <TableBodyRow>
            <TableBodyCell>Any coffee beverage</TableBodyCell>
            <TableBodyCell>5</TableBodyCell>
        </TableBodyRow>
        <TableBodyRow>
            <TableBodyCell>Cat toys cat. 1</TableBodyCell>
            <TableBodyCell>8</TableBodyCell>
        </TableBodyRow>
        <TableBodyRow>
            <TableBodyCell>Any T-Shirt</TableBodyCell>
            <TableBodyCell>30</TableBodyCell>
        </TableBodyRow>

    </TableBody>
    </Table>
    <p class="text-sm">
        Paws can not be used as partial payment. Paws are not convertible to money. Paws expire when you close your account.
        Paws are not transferable.
    </p>
</Modal>