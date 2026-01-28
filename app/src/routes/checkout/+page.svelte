<script lang="ts">

import GlasBox from "$lib/components/GlasBox.svelte";
import {order} from "$lib/stores/order";
import {Accordion, AccordionItem, Button, Checkbox, Heading, Input, Label, Modal, Select} from "flowbite-svelte";
import PaginationTable from "$lib/components/PaginationTable.svelte";
import {onMount} from "svelte";
import {goto} from "$app/navigation";
import {formatCurrency} from "$lib/utils/currency";
import {QuestionCircleSolid} from "flowbite-svelte-icons";
import dayjs from "dayjs";
import {BookingType} from "$lib/_types/BookingType";
import {user} from "$lib/stores/user";
import CreditCard from "$lib/components/CreditCard.svelte";
import GiftCard from "$lib/components/GiftCard.svelte";
import {products} from "$lib/stores/products";

const header = (key: string, value: any) => {
    switch (key) {
        case 'product':
            if(Array.isArray(value)) {
                return '<p class="font-bold">' + (value[0]?.name || 'Perk') + '</p><p>' + (value[1] || '') + '</p>'
            }
        case 'size':
            return value?.size || ''
        case '+':
            return '<p>' + (value?.map((addon: any) => addon?.name).join('</p><p>')  || '') + '</p>'
        case 'price':
            return formatCurrency(value, 'USD')
        case '-': return `<button type="button" data-delete data-item-index="${value}" class="text-white bg-red-500 px-2.5 py-1 rounded-[2.5rem]">x</button>`
    }
    return value ? value : '';
}
function deleteItem(index: number) {
    $order.items = $order.items.filter((item, i) => i !== parseInt(index))
    recalculateSubtotal()
}


let liabilityModal = false;
let ageRestrictionModal = false;
let refundModal = false;

$: taxes = ($order?.subTotal || 0) * ($order?.type !== BookingType.CREDIT_CARD ? 0 : 0.075)
$: fees = ($order?.type === BookingType.CREDIT_CARD ? ($order?.subTotal || 0) * .033 + .3 : ($order?.subTotal || 0) * 0.02)

$: total = ($order?.subTotal || 0) + taxes + fees

const recalculateSubtotal = () => {
    // remove all applied discounts
    $order.items = $order?.items.filter((item) => item.product)
    let pawPotential = $user ? Math.floor($user.paws) : 0;
    if($user?.activePlan){
        $order.items.filter((item) => item.product.type === 'beverage').forEach(upsale => {
            if(upsale.product.paws && pawPotential >= upsale.product.paws){
                const savings = upsale.size ? upsale.size.price : upsale.product.price
                $order.items = [...$order.items, {name: upsale.product.paws + ' paws applied', price: savings * -1}]
                pawPotential -= upsale.paws
            }
        })

        //TODO free visit?
        // discount on own visit
        if($order.items.find(i => i.product.type === 'booking')){
            $order.items = [...$order.items, {name: 'Memberdiscount', price: $products.find(p => p.type === 'booking')?.price * -1 * $user.activePlan.plan.visitationDiscount}]
        }
    }

    $order.subTotal = ($order?.items || []).reduce((prev, curr) => prev + curr.price * (curr?.amount || 1), 0)
}

onMount(async () => {
    if(!$order){
        await goto('/')
    }
    recalculateSubtotal()
    setTimeout(() => {
        const itemRows = document.querySelectorAll('[data-delete]')
        itemRows.forEach((row: any) => {
            row.addEventListener('click', (e: any) => {
                deleteItem(e.target.dataset.itemIndex)
            })
        })
        console.log({orderStore: $order})
    }, 100)

})
</script>
<GlasBox>
    <Heading tag="h3">Checkout</Heading>
    {#if $order}
    <form class="mt-3">
        <Accordion flush>
            <AccordionItem open>
                <span slot="header">Itemization</span>
                <!--<div slot="arrowup"><QuestionCircleSolid/></div>
                <div slot="arrowdown"><QuestionCircleSolid/></div>-->
                <PaginationTable data={$order?.items?.map((item, i) => ({'#': item.amount, product: [item.product || {name: item.name}, item.customization], price: item.price * (item?.amount || 1), size: item.size, '+': item.addons, '-': i}))} renderValue={header}/>
            </AccordionItem>
        </Accordion>
        <Heading tag="h3" class="mt-3">{dayjs($order?.delivery?.value).format('dddd, MMM D, YYYY @ h:mm A')} </Heading>
        <div class="grid gap-3 md:grid-cols-2 mt-3">
            <Label class="space-y-2">
                <span>Name</span>
                <Input required bind:value={$order.name} />
            </Label>
            <Label class="space-y-2">
                <span>Email</span>
                <Input required type="email" bind:value={$order.email} />
            </Label>
            <Label class="space-y-2">
                <span>Phone</span>
                <Input required type="tel" bind:value={$order.phone} />
            </Label>
            <Label class="space-y-2">
                <span>Payment type</span>
                <Select required type="tel" bind:value={$order.type} >
                    <option value={BookingType.CREDIT_CARD}>Credit Card</option>
                    <option value={BookingType.GIFT_CARD}>Gift Card</option>
                </Select>
            </Label>
            <div class="space-y-3 md:col-span-2">

                {#if !$user}
                <Checkbox required>
                    <span>I agree to the strict age minimum of 8 <button type="button" on:click={() => ageRestrictionModal = true} class="text-primary-600">see age restrictions</button></span>
                </Checkbox>
                <Checkbox required>
                    <span>I hereby agree to the <button type="button" on:click={() => liabilityModal = true} class="text-primary-600">liability and release waiver</button></span>
                </Checkbox>
                {/if}
            </div>
            <div class="grid gap-3 grid-cols-2 dark:text-white place-self-start w-full">
                <p>Subtotal</p>
                <p class="place-self-end font-bold">{formatCurrency($order.subTotal, 'USD')}</p>
                <p>Taxes</p>
                <p class="place-self-end font-bold">{formatCurrency(taxes, 'USD')}</p>
                <p>Fees</p>
                <p class="place-self-end font-bold">{formatCurrency(fees, 'USD')}</p>
                <p class="font-semibold text-xl">Total</p>
                <p class="place-self-end font-bold text-xl">{formatCurrency(total, 'USD')}</p>
            </div>
            <div class="border rounded dashed dark:text-white grid">
                <div class="p-2 space-y-2">
                    {#if $order.type === BookingType.CREDIT_CARD}
                        <CreditCard accountName={$order.name}/>
                    {:else}
                        <GiftCard />
                    {/if}
                    <Checkbox required>
                        <span>I agree to the <button type="button" on:click={() => refundModal = true} class="text-primary-600">cancellation/reschedule and refund policy</button></span>
                    </Checkbox>
                    <Button class="w-full" type="submit">Pay now</Button>
                </div>
            </div>
        </div>
    </form>
    {/if}
</GlasBox>
<Modal title="Cancellations" bind:open={refundModal}>
    24 Hour Notice Required to Cancel for a Refund or Reschedule.
</Modal>
<Modal title="Minimum ages" bind:open={ageRestrictionModal}>
    GUESTS MUST BE 8 YEARS OLD. This is a liability issue and not up to parental discretion. Infants /toddlers in strollers are not allowed in the cat lounge. PLEASE NOTE: NO REFUNDS will be provided for reservations denied due to falsified age on minors.
</Modal>
<Modal title="Liability and release waiver" bind:open={liabilityModal}>
    I, hereby certify that I am over eighteen (18) years of age and am of sound mind at the time of execution of this “Release, Hold Harmless, and Indemnification Agreement” (hereafter known as the “Release”). I agree to the following: 1) Lazy Cat Lounge & Cafe has available cats to be played with by customers and agrees to allow me to play with such cats in consideration of both the payment of the fees and my execution of this Release and agreeing to be bound by its terms. 2) I am aware of and fully understand the inherent dangers involved in playing with cats, including the risk of death and/or personal injury or damage to myself or my property while participating in such activities or having my property at the site of such activity. I further acknowledge that participation in such activities may not be covered under the insurance of Lazy Cat Lounge & Cafe and that customers of said café may not have any right to file a claim against Lazy Cat Lounge & Cafe's insurance policy. I freely and voluntarily execute this Release with such knowledge and assume full and sole responsibility for the risk of death, personal injury and/or property loss arising from or in any way connected with my participation in the activities provided by Lazy Cat Lounge & Cafe. 3) I agree to abide by all rules and regulations that Lazy Cat Lounge & Cafe may impose regarding the resident cats. I agree to follow all rules and to undertake all activities in a responsible manner. IF I AM UNWILLING OR UNABLE TO FOLLOW ANY RULES, LAZY CAT LOUNGE & CAFE WILL TERMINATE MY PARTICIPATION IN SUCH ACTIVITIES AND I WILL NOT BE ENTITLED TO ANY REFUND OF FEES. 4) I acknowledge that playing with cats may not be supervised, and Lazy Cat Lounge & Cafe staff may not be with me the entire time I am in contact with the cats, but Lazy Cat Lounge & Cafe staff will remain on the premises to monitor the activity of all participants, offer guidance and encouragement, and be able to assist in the event of participant difficulty. 5) I have no physical or emotional issue(s) including, but not limited to, any allergies, which would adversely affect my ability to play with the cats in a safe and appropriate manner. 6) I agree not to engage in any activity that will injure or otherwise hurt the cats in any manner. 7) I hereby release and forever discharge Lazy Cat Lounge & Cafe, their respective agents, owners, employees, and independent contractors, and their respective sureties, insurers, successors, assigns, and legal representatives, from any liability, claim, cause of action, demand, and damages for any injury, death, and damages of any kind or nature whatsoever to myself or my property as a result of engaging in any activity at Lazy Cat Lounge & Cafe. including, but not limited to, playing with the cats, whether such injury, death, or property damage is caused by the intentional or negligent act or omission on the part of (i) any other customer of Lazy Cat Lounge & Cafe, (ii) any employee, agent, owner, or independent contractor of Lazy Cat Lounge & Cafe, or (iii) any other person at Lazy Cat Lounge & Cafe. Furthermore, I agree to pay any and all attorney fees and costs of Lazy Cat Lounge & Cafe, and any of their respective agents, employees, owners, and independent contractors if I bring any action, claim, or demand against Lazy Cat Lounge & Cafe or any of their respective agents, employees, owners, or independent contractors for any reason for which this Release applies. 8) I agree to defend with counsel chosen by the indemnified party, indemnify and hold harmless Lazy Cat Lounge & Cafe, their respective agents, employees, owners and independent contractors, their insurers, successors, assigns, and legal representatives from any liability, claim, cause of action, demand or damages for injury, death, or damages of any kind or nature whatsoever to any person or their property resulting from any actual or claimed intentional or wrongful act or omission by me arising from or as a result of my presence at Lazy Cat Lounge & Cafe. I agree to provide said defense and indemnity regardless of the merit of the claim. 9) I agree to and hereby bind my heirs, executors, assigns, and all other legal representatives by executing this Release. 10) I hereby acknowledge and agree that this release is intended to be construed and interpreted as broad and inclusive as permitted by the laws of New York. If any portion of this Release is found or declared to be invalid or unenforceable, such invalidity shall not affect the remainder of this Release not found to be invalid and the remainder of this Release shall remain in full force and effect. This agreement shall be governed by the laws of the State of New York (without regard to the laws that might be applicable under principles of conflicts of law, and without regard to the jurisdiction in which any action or special proceedings may be instituted), as to all matters, including but not limited to matters of jurisdiction, validity, property rights, construction, effect, and performance. All disputes shall be subject to litigation only within the courts of the State of New York, County of Ontario. BY EXECUTING THIS RELEASE, I ACKNOWLEDGE THAT I HAVE READ THIS RELEASE, UNDERSTAND THE CONTENT HEREOF, HAVE BEEN ADVISED AND HAD THE OPPORTUNITY TO SEEK INDEPENDENT COUNSEL OF MY CHOICE AND CERTIFY THAT I HAVE FREELY AND VOLUNTARILY EXECUTED THIS RELEASE. I ACKNOWLEDGE THAT, BUT FOR THE EXECUTION OF THIS RELEASE AND AGREEING TO BE BOUND BY THE TERMS HEREOF, LAZY CAT LOUNGE & CAFE WOULD NOT AUTHORIZE ME TO PARTICIPATE IN ANY ACTIVITIES AT LAZY CAT LOUNGE & CAFE. You are signing this agreement electronically. You agree that your electronic signature is the equivalent of your manual signature on this agreement. By typing your name, you agree to be legally bound by the terms and conditions of this Agreement. You also agree that no certification authority or other third party verification is necessary to validate your E-signature and that lack of such certification or third party verification will not in any way affect the enforceability of your E-signature or any resulting contract between you and Lazy Cat Lounge & Cafe, LLC.
</Modal>