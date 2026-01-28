<script lang="ts">
    import {onMount} from "svelte";
    import {get} from "$lib/api";
    import PaginationTable from "$lib/components/PaginationTable.svelte";
    import dayjs from "dayjs";
    import {EnvelopeSolid} from "flowbite-svelte-icons";
    import PaginationControl from "$lib/components/PaginationControl.svelte";
    import GlasBox from "$lib/components/GlasBox.svelte";
    import {Spinner} from "flowbite-svelte";

    export let data;

    let sortBy = 'lastName'
    let search = ''
    $: paginationData = data.members.collection?.map(member => ({
        plan: member.activePlan?.plan?.name || 'PayAsYouGo',
        gender: member.gender === 0 ? 'Mrs' : (member.gender === 1 ? 'Mr' : 'Mx'),
        firstName: member.firstName,
        lastName: member.lastName,
        birthday: dayjs(member.dateOfBirth.value).format('MMM DD'),
        phone: member.phone,
        email: member.email,
        since: dayjs(member.createdAt.value).format('MM/DD/YYYY'),
        actions: member.id
    }))
    const renderValue = (key: string, value: any) => {
        switch (key) {
            case 'email':
                return '<a class="text-primary-500" href="mailto:' + value + '">' + value + '</a>';
            case 'phone':
                return '<a class="text-primary-500" href="tel:' + value + '">' + value + '</a>';
            case 'actions':
                return '<a class="text-primary-500" href="/administration/members/' + value + '">open</a>';
            default:
                return value ? value : 'N/A';
        }

    }

</script>
<GlasBox full>
    {#if data.members?.collection?.length > 0}
        <PaginationTable data={paginationData} {renderValue} />
        <div class="mt-3">
            <PaginationControl bind:paginationResult={data.members} />
        </div>
    {:else}
        <Spinner/>
    {/if}
</GlasBox>

