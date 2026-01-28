<script lang="ts">
    import {Button, Card, Heading, Hr, Input, Label, Modal, P, Select} from "flowbite-svelte";
    import dayjs from "dayjs";
    import {user} from "$lib/stores/user";
    import {onMount} from "svelte";
    import {BookingType} from "$lib/_types/BookingType";
    import {products} from "$lib/stores/products";
    import {get} from "$lib/api";
    import {formatCurrency} from "$lib/utils/currency.js";
    import BeverageUpsaleModal from "$lib/partials/booking/BeverageUpsaleModal.svelte";
    import {CheckCircleSolid, TrashBinSolid} from "flowbite-svelte-icons";
    import {order as orderStore} from "$lib/stores/order";
    import {goto} from "$app/navigation";

    export let open = false;
    export let event;

    let order = {
        name: '',
        email:'',
        pax: 1,
        type: BookingType.CREDIT_CARD,
        upsale: [],
        appliedPerks: [],
        phone: '',
    }
    let upsaleModal = false;
    let upsaleIndex = 0;
    let subTotal = 0;
    let pawPotential = 0;
    let fees = 0;

    $: isWeekend = dayjs(event?.start).format('dddd') === 'Saturday' || dayjs(event?.start).format('dddd') === 'Sunday';

    $:{
        pawPotential = $user ? Math.floor($user.paws) : 0;
        fees = 0;
        order.appliedPerks = []
        order.upsale.forEach(upsale => {
            if(upsale.product.paws && pawPotential >= upsale.product.paws){
                const savings = upsale.size ? upsale.size.price : upsale.product.price
                order.appliedPerks = [...order.appliedPerks, {name: upsale.product.paws + ' paws applied', price: savings * -1}]
                pawPotential -= upsale.paws
            }
        })
        if($user?.activePlan){
            //TODO free visit?
            // discount on own visit
            order.appliedPerks = [...order.appliedPerks, {name: 'Memberdiscount', price: $products.find(p => p.type === 'booking')?.price * -1 * $user.activePlan.plan.visitationDiscount}]
        }
        console.log({order})
        subTotal = ($products.find(p => p.type === 'booking')?.price || 0) * order.pax + order.upsale.reduce((prev, curr) => prev + curr.price, 0) + order.appliedPerks.reduce((prev, curr) => prev + curr.price, 0);
        fees = (order.type === BookingType.CREDIT_CARD ? subTotal * .033 + .3 : subTotal * 0.02);
    }



    const openUpsaleModal = (index: number) => {
        upsaleIndex = index
        upsaleModal = true
    }
    const checkout = async () => {
        $orderStore = {
            name: order.name,
            email: order.email,
            type: order.type,
            phone: order.phone,
            subTotal: subTotal,
            delivery:{value: event.start, datetime: null, stamp: null},
            items: [...order.upsale, {
                product: $products.find(p => p.type === 'booking'),
                amount: order.pax,
                price: $products.find(p => p.type === 'booking')?.price}, ...order.appliedPerks ]
        }
        goto('/checkout')
    }
    onMount(async () => {
        if($products.length === 0){
            $products = await get('/products')
        }
        console.log({user: $user})
        order.name = $user ? $user.firstName + ' ' + $user.lastName : '';
        order.email = $user ? $user.email : '';
        order.phone = $user ? $user.phone : '';

    })
</script>

<Modal bind:open>
    <svelte:fragment slot="header">
        <div class="flex gap-5">
            <span class="text-2xl">Event Booking</span>
            {#if !$user}
                <p>
                    Take advantage of benefits: <Button size="xs" color="alternative" href="/auth">Login or register</Button>
                </p>
                {:else}
                <span class="text-2xl">for {$user.firstName}</span>
            {/if}
        </div>
    </svelte:fragment>
    <Heading tag="h3" >{dayjs(event.start).format('dddd, MMMM D -  h:mm a')}</Heading>
    <Heading tag="h4" >{event.extendedProps.pax >0 ? event.extendedProps.pax: 'No'} slots left</Heading>
    {#if event.extendedProps.pax <1}
        {#if $user}
            <Button color="light" on:click={() => open = false}>Join waitlist</Button>
        {:else}
            <Button color="secondary" href="/auth">Login or register to join waitlist</Button>
        {/if}
    {:else}
        <form on:submit|preventDefault={() => checkout()}>
            <div class="grid gap-3 md:grid-cols-2">
                <div class="space-y-4">

                    <Label class="space-y-2">
                        <span>Number of People</span>
                        <Select bind:value={order.pax}>
                            {#each Array(event.extendedProps.pax).fill(0) as _, i}
                                <option value={i+1}>{i+1}</option>
                            {/each}
                        </Select>
                    </Label>

                    <P>Hate waiting in line? Have your beverage ready at the time of entry.</P>
                    <div class="flex flex-wrap gap-3 my-3">
                        {#each Array(order.pax).fill(0) as _, i}
                            <div class="flex-1 rounded-lg border border-dotted p-3 text-center">
                                    <Button color="light" size="xs" on:click={() => openUpsaleModal(i)}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24" class="{typeof order.upsale[i] === 'undefined' ? 'text-red-600' : 'text-green-600'}">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                <path fill="currentColor" fill-opacity="0" stroke-dasharray="48" stroke-dashoffset="48" d="M17 9v9c0 1.66 -1.34 3 -3 3h-6c-1.66 0 -3 -1.34 -3 -3v-9Z">
                                                    <animate fill="freeze" attributeName="fill-opacity" begin="0.8s" dur="0.5s" values="0;1" />
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="48;0" />
                                                </path>
                                                <path stroke-dasharray="14" stroke-dashoffset="14" d="M17 9h3c0.55 0 1 0.45 1 1v3c0 0.55 -0.45 1 -1 1h-3">
                                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.6s" dur="0.2s" values="14;0" />
                                                </path>
                                                <mask id="lineMdCoffeeFilledLoop0">
                                                    <path stroke="#fff" d="M8 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4M12 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4M16 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4">
                                                        <animateMotion calcMode="linear" dur="3s" path="M0 0v-8" repeatCount="indefinite" />
                                                    </path>
                                                </mask>
                                                <rect width="24" height="0" y="7" fill="currentColor" mask="url(#lineMdCoffeeFilledLoop0)">
                                                    <animate fill="freeze" attributeName="y" begin="0.8s" dur="0.6s" values="7;2" />
                                                    <animate fill="freeze" attributeName="height" begin="0.8s" dur="0.6s" values="0;5" />
                                                </rect>
                                            </g>
                                        </svg>
                                    </Button>

                            </div>
                        {/each}
                    </div>
                    <Label class="space-y-2">
                        <span>Estimate fees & taxes for</span>
                        <Select bind:value={order.type}>
                            <option value="credit card">Credit card</option>
                            <option value="gift-card">Gift card</option>

                        </Select>
                    </Label>
                </div>
                <Card>
                    <div class="grid gap-3 grid-cols-3">
                        <span class="col-span-2">Admission:</span>
                        <span class="place-self-end">{formatCurrency($products.find(p => p.type === 'booking').price * order.pax, 'USD')}</span>
                        {#each order.upsale as upsale, i}
                            <span class="col-span-2 flex gap-2 items-center">
                                <TrashBinSolid class="text-red-500 h-3 w-3 cursor-pointer" on:click={() => order.upsale = order.upsale = order.upsale.filter((_, index) => index !== i)}/>
                                {upsale.product.name}
                            </span>
                            <span class="place-self-end">{formatCurrency(upsale.price , 'USD')}</span>
                        {/each}
                        {#each order.appliedPerks as perk, i}
                            <span class="col-span-2 flex gap-2 items-center">
                                {perk.name}
                            </span>
                            <span class="place-self-end">{formatCurrency(perk.price , 'USD')}</span>
                        {/each}
                        <span class="col-span-3"><Hr/></span>
                        <span class="col-span-2">Subtotal:</span>
                        <span class="place-self-end">{formatCurrency(subTotal , 'USD')}</span>
                        <span class="col-span-2">Taxes:</span>
                        <span class="place-self-end">{formatCurrency(subTotal * (order.type !== BookingType.CREDIT_CARD ? 0 : 0.075) , 'USD')}</span>
                        <span class="col-span-2">Fees:</span>
                        <span class="place-self-end">{formatCurrency(fees , 'USD')}</span>
                        <span class="col-span-2 font-bold">Total:</span>
                        <span class="place-self-end font-bold">{formatCurrency((subTotal * 1.075)  + fees , 'USD')}</span>
                    </div>
                    <Button class="mt-5" type="submit">Checkout</Button>
                </Card>
            </div>

        </form>

    {/if}
</Modal>
<BeverageUpsaleModal bind:open={upsaleModal} on:selected={(e) => order.upsale[upsaleIndex] = e.detail }/>