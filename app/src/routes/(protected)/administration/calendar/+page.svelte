<script lang="ts">
    import GlasBox from "$lib/components/GlasBox.svelte";
    import {Button, Checkbox, Heading, Input, Label, P} from "flowbite-svelte";

    export let data;
    const {openingHours} = data;
    let openingHoursCafe = [
        {
            day: "Sunday",
            open: "11:00",
            close: "17:00"
        },
        {
            day: "Monday",
            open: null,
            close: null
        },
        {
            day: "Tuesday",
            open: "15:00",
            close: "20:00"
        },
        {
            day: "Wednesday",
            open: "13:00",
            close: "20:00"
        },
        {
            day: "Thursday",
            open: "15:00",
            close: "20:00"
        },
        {
            day: "Friday",
            open: "13:00",
            close: "20:00"
        },
        {
            day: "Saturday",
            open: "11:00",
            close: "20:00"
        },
    ]
</script>

<GlasBox full>
    <Heading tag="h3">Calendar</Heading>
    <Heading tag="h4">Opening hours cafe</Heading>
    <form>
        <div class="grid grid-cols-7 gap-2">
            {#each openingHours.openingHoursCafe as day}
                <div class="grid gap-2">
                    <P>{day.day}</P>
                    <input type="hidden" name="day" value="{day.day}">
                    <Label class="space-y-2">
                        <span>Opening</span>
                        <Input name="open" type="time" bind:value={day.open}/>
                    </Label>
                    <Label class="space-y-2">
                        <span>Close</span>
                        <Input name="close" type="time" bind:value={day.close}/>
                    </Label>
                    <Button type="button" on:click={() => day.open = day.close = null} size="xs" color="red">Clear</Button>
                </div>

            {/each}
        </div>
    </form>


</GlasBox>