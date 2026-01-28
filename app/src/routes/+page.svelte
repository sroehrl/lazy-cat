<script lang="ts">
    import {Banner, Button, Hr, Modal, Spinner, TabItem, Tabs} from "flowbite-svelte";

    import {onMount} from "svelte";
    import {get} from "$lib/api";
    import GlasBox from "$lib/components/GlasBox.svelte";
    import dayjs from "dayjs";
    import ImAtTheMallExplanationModal from "$lib/partials/plans/ImAtTheMallExplanationModal.svelte";

    let openingHours:{openingHoursCafe: any[], openingHoursLounge: any[], blockoutDays: any[]} = []

    let banner = [];
    let now = dayjs()
    let mallLocationModal = false

    const currentlyOpen = () => {
        const day = now.format('dddd')
        const time = now.format('HH:mm')
        if(!openingHours.openingHoursCafe.find((bod) => bod.day === day && bod.open <= time && bod.close >= time)){
            return false;
        }
        if(openingHours.blockoutDays.find((bod) => bod.id === now.format('YYYY-MM-DD'))){
            return false
        }
        return true
    }

    onMount(async() => {
        openingHours = await get('/settings/opening-hours')

        banner = openingHours.blockoutDays.filter((bod) => dayjs(bod.id).format('YYYY-MM-DD') === now.format('YYYY-MM-DD'))

    })
</script>
<Modal bind:open={mallLocationModal} size="xl">
    <img src="/mall-location.png">
</Modal>
<div class="w-4/5 lg:max-w-screen-lg mx-auto mb-24">
    <section >
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">

            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl lg:text-6xl text-white">Lazy Cat Lounge & Cafe</h1>
            <button on:click={() => mallLocationModal = true} class="mb-4 text-2xl font-extrabold tracking-tight leading-none md:text-3xl lg:text-4xl text-neutral-300">@ Eastview Mall</button>
            <div>

                <Button href="/calendar" color="light" on:click={() => mallLocationModal = true} class="text-xl md:text-2xl lg:text-3xl ">Book a visit</Button>
            </div>
        </div>

    </section>
    <div class="w-full flex justify-end">
        <GlasBox full>
            <div class="w-72">

            </div>
            <h1 class="text-3xl font-bold text-primary-600 mb-3">Opening Hours</h1>
            {#if openingHours.openingHoursCafe}
                <Tabs>
                    <TabItem title="Status" open>
                        {#if currentlyOpen()}
                            <p class="text-primary-500 text-lg">Drop by, we're open</p>
                        {:else}
                            <p class="text-red-500 text-lg">Currently Closed</p>
                        {/if}
                        <Hr/>
                        <div class="dark:text-white">
                            <p>We're closed on:</p>
                            {#each openingHours.blockoutDays.sort((a, b) => dayjs(a.id).diff(dayjs(b.id))) as day}
                                <p>{dayjs(day.id).format('MM/DD')} {day.title}</p>
                            {/each}
                        </div>


                    </TabItem>
                    {#each ['Cafe', 'Lounge'] as tab}
                        <TabItem title={tab}>
                            {#each openingHours['openingHours' + tab] as day}
                                <div class="flex justify-between dark:text-white">
                                    <p>{day.day}</p>
                                    {#if day.open}
                                        <p>{day.open} - {day.close}</p>
                                    {:else}
                                        <p>closed</p>
                                    {/if}

                                </div>
                            {/each}
                        </TabItem>
                    {/each}
                </Tabs>
            {:else}
                <Spinner/>
            {/if}
        </GlasBox>
    </div>
</div>
{#if banner.length> 0}
    <Banner position="fixed" >
        <div class="dark:text-white">
            <p><span class="text-red-600 font-bold">We're currently closed:</span> {banner[0].title}</p>
        </div>
    </Banner>
{/if}

