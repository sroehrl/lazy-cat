<script lang="ts">
    import { Heading, P, Button } from "flowbite-svelte";
    import type { Resident } from "$lib/_types/Resident";
    import ResidentCard from "$lib/components/residents/ResidentCard.svelte";
    import type { PageData } from "./$types";
    import { base } from "$app/paths";

    export let data: PageData;

    $: residents = data.residents as Resident[];
</script>

<svelte:head>
    <title>Meet Our Residents | Lazy Cat Lounge & Cafe</title>
    <meta
        name="description"
        content="Meet the adorable feline inhabitants of Lazy Cat Lounge & Cafe. All our residents are available for adoption!"
    />
</svelte:head>

<div class="relative overflow-hidden bg-secondary-900 text-white mb-12 py-3">
    <div class="max-w-screen-xl px-4 mx-auto relative z-10">
        <div class="m">
            <header class="mb-8">
                <Heading
                    tag="h1"
                    class="mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl lg:text-6xl text-white"
                >
                    Meet Our <span class="text-primary-500">Purr-fect</span> Residents
                </Heading>
                <P class="text-lg font-normal text-gray-300 lg:text-xl">
                    Our residents are the heart and soul of the cafe. Each one
                    has a unique personality and a story to tell. Whether
                    they're lounging in a sunbeam or chasing a feather, they're
                    always ready for a new friend.
                </P>
            </header>
            <div
                class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4"
            >
                <Button href="{base}/calendar" size="lg" color="primary"
                    >Book a Visit</Button
                >
            </div>
        </div>
    </div>
</div>

<div id="residents" class="max-w-screen-xl px-4 mx-auto mb-20 my-5">
    <div
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"
    >
        {#each residents as resident (resident.id)}
            <ResidentCard {resident} readOnly={true} />
        {:else}
            <div
                class="col-span-full py-20 text-center bg-gray-50 dark:bg-neutral-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700"
            >
                <P class="text-xl text-gray-500 italic"
                    >Our feline friends are currently taking a nap. Please check
                    back later!</P
                >
            </div>
        {/each}
    </div>

    <div
        class="mt-5 bg-primary-50 dark:bg-neutral-800 p-5 rounded-3xl text-center"
    >
        <Heading tag="h3" class="text-2xl font-bold mb-4"
            >Want to give a cat a forever home?</Heading
        >
        <P class="mb-6 max-w-2xl mx-auto text-gray-700 dark:text-gray-300">
            Adoption is a lifetime commitment. We work closely with local
            rescues to ensure our residents find the perfect match. Click the
            button below to learn more about our adoption process.
        </P>
        <Button href="/adoption-info" color="primary"
            >Adoption Information</Button
        >
    </div>
</div>

<style>
    :global(html) {
        scroll-behavior: smooth;
    }
</style>
