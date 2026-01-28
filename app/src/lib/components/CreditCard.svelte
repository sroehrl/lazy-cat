<script lang="ts">
    import {Input, Label} from "flowbite-svelte";
    import {onMount} from "svelte";

    export let accountName = '';

    let cc = {
        name: '',
        number: '',
        expiry: '',
        cvc: '',
        zip: '',
    }
    onMount(() => {
        cc.name = accountName
    })
</script>

<div class="grid gap-3">
    <Label class="space-y-2">
        <span>Name on Card</span>
        <Input required bind:value={cc.name}/>
    </Label>
    <Label class="space-y-2">
        <span>Number</span>
        <Input pattern="^([0-9]{4}( |\\-)){3}[0-9]{4}$" required bind:value={cc.number} placeholder="4242 4242 4242 4242"/>
    </Label>
    <div class="flex gap-2">
        <Label class="space-y-2">
            <span>Expiration Date</span>
            <Input pattern="(0?[1-9]|1[0-2])/\d{2}" required bind:value={cc.expiry} placeholder="MM/YY"/>
        </Label>
        <Label class="space-y-2">
            <span>CVC</span>
            <Input type="number" required bind:value={cc.cvc} placeholder="123"/>
        </Label>
    </div>
</div>
