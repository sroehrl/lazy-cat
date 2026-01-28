<script>
    import {onMount} from "svelte";
    import { page } from '$app/stores';
    import {goto} from "$app/navigation";
    import {Sidebar, SidebarGroup, SidebarItem, SidebarWrapper} from "flowbite-svelte";
    import {CashSolid, ChartPieSolid, ClipboardListSolid, UserCircleSolid} from "flowbite-svelte-icons";
    import Calender from "flowbite-svelte/Calender.svelte";

    export let data;

</script>
<div class="flex gap-5">
    <Sidebar class="w-fit md:w-64" activeUrl={$page.url.pathname}>
        <SidebarWrapper class="overflow-y-auto py-4 px-3 bg-secondary-600 rounded dark:bg-neutral-900">
            <SidebarGroup>
                <SidebarItem href="/member">
                    <svelte:fragment slot="subtext">
                        <span class="hidden md:block">Dashboard</span>
                    </svelte:fragment>
                    <svelte:fragment slot="icon">
                        <ChartPieSolid class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    </svelte:fragment>
                </SidebarItem>
                <SidebarItem href="/calendar">
                    <svelte:fragment slot="subtext">
                        <span class="hidden md:block">Calendar</span>
                    </svelte:fragment>
                    <svelte:fragment slot="icon">
                        <Calender class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    </svelte:fragment>
                </SidebarItem>
                <SidebarItem href="/member/plan">
                    <svelte:fragment slot="subtext">
                        <span class="hidden md:block">Membership</span>
                    </svelte:fragment>
                    <svelte:fragment slot="icon">
                        <ClipboardListSolid class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    </svelte:fragment>
                </SidebarItem>
                <SidebarItem href="/member/paws">
                    <svelte:fragment slot="subtext">
                        <span class="hidden md:block">Paws</span>
                    </svelte:fragment>
                    <svelte:fragment slot="icon">
                        <CashSolid class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    </svelte:fragment>
                </SidebarItem>
                <SidebarItem href="/member/account">
                    <svelte:fragment slot="subtext">
                        <span class="hidden md:block">Account</span>
                    </svelte:fragment>
                    <svelte:fragment slot="icon">
                        <UserCircleSolid class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
                    </svelte:fragment>
                </SidebarItem>
            </SidebarGroup>
        </SidebarWrapper>
    </Sidebar>
    <slot/>
</div>
