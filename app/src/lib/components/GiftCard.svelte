<script>
    import {Input, Label} from "flowbite-svelte";

    let giftCard = {
        code: '',
    }

</script>

<div class="grid gap-3">
    <Label class="space-y-2">
        <span>Gift Card Code</span>
        <Input required bind:value={giftCard.code}/>
    </Label>
</div>