<script lang="ts">
    import {Button, Heading} from "flowbite-svelte";
    import GlasBox from "$lib/components/GlasBox.svelte";
    import PawExplanationModal from "$lib/partials/plans/PawExplanationModal.svelte";
    import ImAtTheMallExplanationModal from "$lib/partials/plans/ImAtTheMallExplanationModal.svelte";
    import {QuestionCircleSolid} from "flowbite-svelte-icons";

    export let data;

    const {user} = data;
    let explainPawsModal = false
    let explainIATMModal = false
</script>

<GlasBox>
    <Heading tag="h1" class="dark:text-neutral-200 text-xl">Welcome,  {user?.firstName}</Heading>
    <div class="grid gap-3 grid-cols-2 dark:text-white">
        <div class="border-r">Paws: {user?.paws || 0} <QuestionCircleSolid class="w-4 inline cursor-pointer" on:click={() => explainPawsModal = true}/></div>
        <div>Plan: <a href="/member/plan" class="whitespace-nowrap">{user?.activePlan?.plan?.name || 'Pay as you go'}</a> </div>
        <div class="border-r">
            <Button size="xs">I'm at the mall</Button> <QuestionCircleSolid class="w-4 inline cursor-pointer" on:click={() => explainIATMModal = true}/>
        </div>
        <div><Button size="xs" color="light">Calendar</Button></div>
    </div>
</GlasBox>
<PawExplanationModal bind:open={explainPawsModal}/>
<ImAtTheMallExplanationModal bind:open={explainIATMModal}/>