<script>
    import {Modal, Table, TableBody, TableBodyCell, TableBodyRow, TableHead, TableHeadCell} from "flowbite-svelte";
export let open = false;
</script>

<Modal bind:open title="I'm at the mall!">
    <p>Members with a subscription plan can let us know when they are at the mall via the "I'm at the mall" feature.</p>
    <p>If the situation allows, we'll place you on the waiting list for a visit and prioritize you when cancellations occur.</p>
    <p class="text-lg">Keep an eye on your phone! You might have to make quick decisions!</p>


</Modal>