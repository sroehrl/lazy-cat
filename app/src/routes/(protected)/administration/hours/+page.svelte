<script lang="ts">
    import GlasBox from "$lib/components/GlasBox.svelte";
    import {Accordion, AccordionItem, Button, Checkbox, Heading, Input, Label, Modal, P} from "flowbite-svelte";
    import {put, get} from "$lib/api";
    import {onMount} from "svelte";
    import {Calendar} from "@fullcalendar/core";
    import dayGridPlugin from "@fullcalendar/daygrid";
    import interactionPlugin from '@fullcalendar/interaction';
    import dayjs from "dayjs";
    import {toast} from "$lib/utils/toast";

    export let data;

    const { hours } = data;

    let { openingHoursCafe, openingHoursLounge, blockoutDays, customHoursCafe, customHoursLounge } = hours;

    let calendarElement: HTMLDivElement;
    let calendar;
    let detailModal = false;
    let eventInfo;
    let newEventInfo = {
        day:'',
        type:'',
        start:'',
        end:'',
        title:''
    }
    const storeOpeningHours = async () => {
        try{
            await put('/settings/opening-hours', {openingHoursCafe, openingHoursLounge, blockoutDays, customHoursCafe, customHoursLounge})
            toast.success('Hours saved')
        } catch (e) {
            toast.error('Something went wrong: ' + e)
        }

    }

    const generateEvents = () => {
        const events = [];
        const firstDayOfMonth = dayjs().startOf('month')
        for(let i = 0; i < 91; i++) {
            const current = firstDayOfMonth.clone().add(i, 'days')
            // blacked out?
            const blockedOut = blockoutDays.find(x => x.start === current.format('YYYY-MM-DD'))
            if(blockedOut){
                events.push(blockedOut);
                continue;
            }
            // Cafe
            let currentCafeHours = openingHoursCafe.find(item => item.day === current.format('dddd'))
            const customCafe = customHoursCafe.find(item => item.id === current.format('YYYY-MM-DD'))
            if(customCafe) {
                currentCafeHours = customCafe;
                currentCafeHours.type = 'customCafe';
                events.push(currentCafeHours)
            } else {
                events.push({
                    allDay: false,
                    title: 'Cafe',
                    start: current.format('YYYY-MM-DD ') + currentCafeHours.open,
                    end: current.format('YYYY-MM-DD ') + currentCafeHours.close,
                    color: '#b62dbc',
                    className: 'cursor-pointer',
                    extendedProps: {
                        type: currentCafeHours?.type || 'Cafe'
                    }
                })
            }


            // Lounge
            let currentLoungeHours = openingHoursLounge.find(item => item.day === current.format('dddd'))
            const customLounge = customHoursLounge.find(item => item.id === current.format('YYYY-MM-DD'))
            if(customLounge) {
                currentLoungeHours = customLounge;
                currentLoungeHours.type = 'customLounge';
                events.push(currentLoungeHours)
            } else {
                events.push({
                    allDay: false,
                    title: 'Lounge',
                    start: current.format('YYYY-MM-DD ') + currentLoungeHours.open,
                    end: current.format('YYYY-MM-DD ') + currentLoungeHours.close,
                    color: '#d45a1d',
                    className: 'cursor-pointer',
                    extendedProps: {
                        type: currentLoungeHours?.type || 'Lounge'
                    }
                })
            }

        }
        return events;
    }

    const removeEvent = () => {
        switch(eventInfo.extendedProps.type) {
            case 'customCafe': customHoursCafe = [...customHoursCafe.filter(event => event.id !== eventInfo.id)]; break;
            case 'customLounge': customHoursLounge = [...customHoursLounge.filter(event => event.id !== eventInfo.id)]; break;
            case 'Blockout': blockoutDays = [...blockoutDays.filter(event => event.id !== eventInfo.id)]; break;
        }
        eventInfo.remove()
        detailModal = false;
        setTimeout(()=> {
            calendar.removeAllEvents();
            generateEvents().forEach(ev => {
                calendar.addEvent(ev)
            });
        })


    }

    const createEvent = () => {
        console.log({newEventInfo, ev: calendar.getEvents()[8]})
        const newEvent = {
            id: newEventInfo.day,
            allDay: false,
            start: newEventInfo.day + ' ' + newEventInfo.start,
            end: newEventInfo.day + ' ' + newEventInfo.end,
            title:newEventInfo.title,
            extendedProps: {
                type: newEventInfo.type
            }
        }
        calendar.addEvent(newEvent)
        if(['Cafe', 'Lounge'].includes(newEventInfo.type)) {
            if(newEventInfo.type === 'Cafe'){
                customHoursCafe = [...customHoursCafe, newEvent]
            } else {
                customHoursLounge = [...customHoursLounge, newEvent]
            }

            const preExisting = calendar.getEvents().find((x) => x.extendedProps.type === newEventInfo.type && x.startStr.startsWith(newEvent.id))
            if(preExisting){
                preExisting.remove()
            }
        }
        detailModal = false;

    }

    onMount(async() => {
        console.log({data})
        if(hours.openingHoursCafe.length){
            openingHoursCafe = hours.openingHoursCafe
        }
        openingHoursLounge = hours.openingHoursLounge.length > 0 ? hours.openingHoursLounge : [...openingHoursCafe]


        calendar = new Calendar(calendarElement, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            views: {
                dayGridMonth: {
                    displayEventEnd: true,
                }
            },
            // events: [...blockoutDays.map(x => ({...x, extendedProps: {type: 'Blockout'}})), ...generateEvents()],
            events: [...generateEvents()],
            dateClick: (info) => {
                const reason = prompt('We\'re close today because:')
                if(reason){
                    const newEvent = {id: info.dateStr, allDay: true, start: info.dateStr, title:reason, extendedProps: {type: 'Blockout'}}
                    blockoutDays = [...blockoutDays, newEvent]
                    calendar.getEvents().forEach(ev => {
                        if(ev.startStr.startsWith(info.dateStr)) {
                            ev.remove()
                        }
                    })
                    calendar.addEvent(newEvent)

                }
            },
            eventClick: (info) => {
                console.log({info})
                eventInfo = info.event
                newEventInfo = {
                    day: dayjs(eventInfo.start).format('YYYY-MM-DD'),
                    title: eventInfo.title,
                    start: dayjs(eventInfo.start).format('HH:mm'),
                    end: dayjs(eventInfo.end).format('HH:mm'),
                    type: eventInfo.extendedProps?.type
                }
                detailModal = true;
                /*if(confirm(`Are you sure you want to remove ${info.event.title}?`)){
                    info.event.remove()
                    blockoutDays = [...blockoutDays.filter(event => event.id !== info.event.id)]
                }*/
            }
        })
        calendar.render();
    })

</script>
<Modal title={newEventInfo.title} bind:open={detailModal}>
    {#if ['Cafe', 'Lounge'].includes(newEventInfo.type)}
        <P>Derived from general Hours</P>
        <form on:submit|preventDefault={createEvent}>
            <input type="hidden" value="customHours{newEventInfo.type}">
            <div class="grid gap-3 grid-cols-2">
                <Label class="space-y-2">
                    <span>Open</span>
                    <Input type="time" bind:value={newEventInfo.start}/>
                </Label>
                <Label class="space-y-2">
                    <span>Close</span>
                    <Input type="time" bind:value={newEventInfo.end}/>
                </Label>
                <div></div>
                <div class="place-self-end">
                    <Button type="submit">Store custom hours for {newEventInfo.day}</Button>
                </div>
            </div>
        </form>
    {:else}
        <P>{eventInfo.extendedProps.type}: {dayjs(newEventInfo.day).format('ddd, MM/DD')}</P>
        {#if !eventInfo.allDay}
            <P><span class="font-bold">{newEventInfo.title}</span> {dayjs(newEventInfo.day+' '+newEventInfo.start).format('h:mma')} - {dayjs(newEventInfo.day+' '+newEventInfo.end).format('h:mma')}</P>
        {/if}
        <P>This is a custom entry. Do you want to remove it?</P>
        <Button color="red" on:click={removeEvent}>remove</Button>
    {/if}

</Modal>

<GlasBox full>
    <Heading tag="h3">Hours</Heading>

    <form class="mt-3" on:submit|preventDefault={storeOpeningHours}>
        <Accordion>
            <AccordionItem>
                <div slot="header">
                    <Heading tag="h4">General opening hours cafe</Heading>
                </div>
                <div class="grid grid-cols-7 gap-2">
                    {#each openingHoursCafe as day}
                        <div class="grid gap-2">
                            <P>{day.day}</P>
                            <Label class="space-y-2">
                                <span>Opening</span>
                                <Input type="time" bind:value={day.open}/>
                            </Label>
                            <Label class="space-y-2">
                                <span>Closing</span>
                                <Input type="time" bind:value={day.close}/>
                            </Label>
                            <Button type="button" on:click={() => day.open = day.close = null} size="xs" color="red">Clear</Button>
                        </div>

                    {/each}
                </div>
            </AccordionItem>
            <AccordionItem>
                <div slot="header">
                    <Heading tag="h4">General opening hours lounge</Heading>
                </div>
                <div class="grid grid-cols-7 gap-2">
                    {#each openingHoursLounge as day}
                        <div class="grid gap-2">
                            <P>{day.day}</P>
                            <Label class="space-y-2">
                                <span>Opening</span>
                                <Input type="time" bind:value={day.open}/>
                            </Label>
                            <Label class="space-y-2">
                                <span>Closing</span>
                                <Input type="time" bind:value={day.close}/>
                            </Label>
                            <Button type="button" on:click={() => day.open = day.close = null} size="xs" color="red">Clear</Button>
                        </div>

                    {/each}
                </div>
            </AccordionItem>
        </Accordion>
        <div class="m-3">
            <Heading tag="h4" class="mt-3">Custom hours</Heading>
            <div class="w-full h-min my-3" bind:this={calendarElement}/>
        </div>




        <div class="grid place-items-end mt-3">
            <Button type="submit">Save</Button>
        </div>
    </form>


</GlasBox>