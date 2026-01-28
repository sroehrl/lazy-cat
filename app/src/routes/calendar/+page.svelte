<script lang="ts">
    import {onMount} from "svelte";
    import {Calendar} from "@fullcalendar/core";
    import dayGridPlugin from '@fullcalendar/daygrid'
    import timeGridPlugin from '@fullcalendar/timegrid'
    import interactionPlugin from '@fullcalendar/interaction'
    import {Spinner} from "flowbite-svelte";
    import {get} from "$lib/api";
    import dayjs from "dayjs";
    import EventBookingModal from "$lib/partials/booking/EventBookingModal.svelte";
    import GlasBox from "$lib/components/GlasBox.svelte";

    export let data;

    let {hours, reserved} = data;

    let calendarElement: HTMLDivElement;
    let calendar;
    let bookingModal = false;
    let event;
    onMount(async () => {
        console.log({data})
        const now = dayjs().set('minutes', 0).set('seconds', 0).set('milliseconds', 0);
        const events = [];
        for(let i = 0; i < 60; i++) {
            const current = now.add(i, 'day');
            // is blacked out or closed
            if(hours.blockoutDays.find((bod) => bod.id === current.format('YYYY-MM-DD')) || hours.openingHoursLounge.find((ohl) => ohl.day === current.format('dddd') && !ohl.open)){
                events.push({
                    title: 'Closed',
                    allDay: true,
                    start: current.toDate(),
                    classNames: 'bg-red-500',
                    eventType: 'blackout'
                })
            } else {
                const custom = hours.customHoursLounge.find(x => x.id === current.format('YYYY-MM-DD'));
                let opening, closing;
                if(custom) {
                    opening = dayjs(custom.start);
                    closing = dayjs(custom.end);
                } else {
                    opening = dayjs(current.format('YYYY-MM-DD ') + hours.openingHoursLounge.find((ohl) => ohl.day === current.format('dddd') && ohl.open).open, 'HH:mm');
                    closing = dayjs(current.format('YYYY-MM-DD ') + hours.openingHoursLounge.find((ohl) => ohl.day === current.format('dddd') && ohl.close).close, 'HH:mm');
                }

                let potentialSlot = dayjs().isAfter(opening) ? dayjs() : opening.clone();
                let pauseRunner = 0;
                while (potentialSlot.isBefore(closing)) {

                    const occupied = reserved.find((r) => r.slot === potentialSlot.format('YYYY-MM-DD HH:mm:ss'));

                    const seats = 6 - parseInt(occupied?.reserved || '0');

                    events.push({
                        title: seats + ' slots left',
                        allDay: false,
                        start: potentialSlot.toDate(),
                        end: potentialSlot.add(25, 'minute').toDate(),
                        classNames: 'min-h-[3.4em] cursor-pointer' + (seats > 2 ? ' bg-secondary-500' : (seats > 0 ? ' bg-yellow-500' : ' bg-red-500')),
                        display: 'block',
                        eventType: 'slot',
                        pax: seats

                    })
                    if(pauseRunner < 3) {
                        potentialSlot = potentialSlot.add(30, 'minute')
                        pauseRunner++;
                    } else {
                        potentialSlot = potentialSlot.add(60, 'minute')
                        pauseRunner = 0;
                    }
                }
            }
        }

        calendar = new Calendar(calendarElement, {
            plugins: [timeGridPlugin, dayGridPlugin, interactionPlugin],
            initialView: 'timeGridDay',
            initialDate: dayjs().hour() > 15 ? dayjs().add(1, 'day').toDate() : dayjs().toDate(),
            expandRows: true,
            events: events,
            slotMinTime: '10:30:00',
            slotMaxTime: '20:30:00',
            nowIndicator: true,
            slotLabelClassNames: 'dark:text-white',
            dayHeaderClassNames: 'dark:text-white',
            dayCellClassNames: 'dark:text-white',
            headerToolbar: {
                left: 'prev,next today',
                // center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            datesSet: (info) => {
                console.log('todo: implement store to save user preference')
            },
            eventClick: (info) => {
                event = info.event
                if(info.event.extendedProps.eventType === 'slot') {
                    bookingModal = true
                }
            }
        })
        calendar.render();
    })
</script>
<GlasBox >
    {#if !calendar}
        <div class="grid place-items-center">
            <Spinner size={24}/>
        </div>
    {/if}
    <div class="w-full min-h-[60vh]" bind:this={calendarElement}/>

</GlasBox>

<!--<div class="bg-white dark:bg-neutral-500 rounded-md border md:w-3/4 lg:w-1/2 mx-3 md:mx-auto p-3">
    <div class="w-full min-h-[60vh]" bind:this={calendarElement}/>
    {#if !calendar}
        <Spinner/>
    {/if}
</div>-->
<EventBookingModal {event} bind:open={bookingModal}/>

