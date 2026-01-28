<script lang="ts">
    import {onMount} from "svelte";
    import {get} from "$lib/api";
    import PlanQuickview from "$lib/partials/plans/PlanQuickview.svelte";
    import {token} from "$lib/stores/auth";
    import {user} from "$lib/stores/user";
    import PawExplanationModal from "$lib/partials/plans/PawExplanationModal.svelte";
    import {QuestionCircleSolid} from "flowbite-svelte-icons";
    import ImAtTheMallExplanationModal from "$lib/partials/plans/ImAtTheMallExplanationModal.svelte";
    import GlasBox from "$lib/components/GlasBox.svelte";

    let explainPawsModal = false
    let explainIATMModal = false
    let plans = [];
    let payAsYouGo = {
        id: 0,
        name: 'Pay as you go',
        price: 0,
        description: 'Membership for as little as $0.00 per month.',
        weekdays: false,
        weekends: false,
        guest: false,
        visitationDiscount: 0,
        pawsPerMonth: 0,
        pricePerMonth: 0
    };
    onMount(async () => {
        plans = (await get('/plans')).collection
        if($token){
            if(!$user){
                $user = (await get('/user'))
            }
        }
    })
</script>
<GlasBox>
    <div class="format mb-5 dark:text-neutral-200">
        <h1 class="dark:text-neutral-200">We're in it for the cats!</h1>
        <p>Our membership plans help support the care of our cats and we're always happy to find feline-friends who help us.</p>
        <p>Being a member not only supports our cats, but all Plans (with the exception of Pay as you go) give you the following perks:</p>
        <ul>
            <li>Paws <QuestionCircleSolid class="w-4 inline cursor-pointer" on:click={() => explainPawsModal = true}/></li>
            <li>"I'm at the mall"-feature <QuestionCircleSolid class="w-4 inline cursor-pointer" on:click={() => explainIATMModal = true}/> </li>
        </ul>
    </div>
    <div class="grid gap-3 md:grid-cols-2">
        <PlanQuickview plan={payAsYouGo} selected={$user?.activePlan === null}/>
        {#each plans as plan}
            <PlanQuickview plan={plan} selected={$user?.activePlan?.id === plan.id}/>
        {/each}
    </div>
</GlasBox>

<PawExplanationModal bind:open={explainPawsModal}/>
<ImAtTheMallExplanationModal bind:open={explainIATMModal}/>

