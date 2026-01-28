<script lang="ts">
    import {Button, Heading, P} from "flowbite-svelte";
    import {formatCurrency} from "$lib/utils/currency.js";
    import {onMount} from "svelte";
    import {token} from "$lib/stores/auth";
    import {user} from "$lib/stores/user";
    import {get} from "$lib/api";

    export let plan;

    export let selected;


</script>
<div class="border rounded shadow-lg p-5">
    <Heading tag="h3" class="mb-2 dark:text-neutral-100">{plan.name}</Heading>
    <P class="h-24">{plan.description}</P>
    <div class="grid gap-2 grid-cols-2 place-items-center">
        <P>Paws:</P>
        <P>{plan.pawsPerMonth} / month</P>
        <P>Visitation discount:</P>
        <P>{plan.visitationDiscount * 100}% off</P>
        <P>Weekdays: </P>
        <P>{plan.weekdays ? '1 free visit per week' : 'Discount applies'}</P>
        <P>Weekends/Holidays: </P>
        <P>{plan.weekends ? '1 free visit per month' : 'Discount applies'}</P>
        <P>Guest:</P>
        <P>{plan.guest ? 'Yes, bring one guest' : 'No free guest'}</P>

    </div>
    <div class="rounded bg-secondary-600 p-3 mt-7">
        <P class="text-center font-bold">Price: {formatCurrency(plan.pricePerMonth, 'USD')} / month</P>
        <P class="text-center font-bold">or save additionally:</P>
        <P class="text-center font-bold"><span class="line-through">{formatCurrency(plan.pricePerMonth * 12, 'USD')}</span> {formatCurrency(plan.pricePerMonth * 10.5, 'USD')} / year</P>
    </div>
    {#if $user}
        <div class="grid mt-3">
            {#if !selected}
                <Button color="alternative" href="/member?plan={plan.id}">Switch to {plan.name}</Button>
            {:else}
                <Button color="primary" >Currently active</Button>
            {/if}
        </div>
    {:else}
        <div class="grid mt-3">
            <Button color="alternative" href="/auth">Sign in to choose</Button>
        </div>

    {/if}

</div>