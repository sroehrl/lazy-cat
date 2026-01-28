<script>
    import { page } from "$app/stores";
    import {
        Sidebar,
        SidebarGroup,
        SidebarItem,
        SidebarWrapper,
        BottomNav,
        BottomNavItem,
    } from "flowbite-svelte";
    import {
        CashSolid,
        ChartPieSolid,
        ClipboardListSolid,
        UserCircleSolid,
        CalendarMonthSolid,
    } from "flowbite-svelte-icons";

    export let data;
</script>

<div class="flex flex-col md:flex-row gap-5 h-full">
    <!-- Desktop Sidebar - Hidden on Mobile -->
    <Sidebar
        class="hidden md:block w-64 shrink-0"
        activeUrl={$page.url.pathname}
    >
        <SidebarWrapper
            class="overflow-y-auto py-4 px-3 bg-secondary-600 rounded-xl dark:bg-neutral-900 h-full shadow-lg"
        >
            <SidebarGroup>
                <SidebarItem
                    href="/member"
                    label="Dashboard"
                    active={$page.url.pathname === "/member"}
                >
                    <svelte:fragment slot="icon">
                        <ChartPieSolid
                            class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        />
                    </svelte:fragment>
                </SidebarItem>
                <SidebarItem
                    href="/calendar"
                    label="Calendar"
                    active={$page.url.pathname === "/calendar"}
                >
                    <svelte:fragment slot="icon">
                        <CalendarMonthSolid
                            class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        />
                    </svelte:fragment>
                </SidebarItem>
                <SidebarItem
                    href="/member/plan"
                    label="Membership"
                    active={$page.url.pathname === "/member/plan"}
                >
                    <svelte:fragment slot="icon">
                        <ClipboardListSolid
                            class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        />
                    </svelte:fragment>
                </SidebarItem>
                <SidebarItem
                    href="/member/paws"
                    label="Paws"
                    active={$page.url.pathname === "/member/paws"}
                >
                    <svelte:fragment slot="icon">
                        <CashSolid
                            class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        />
                    </svelte:fragment>
                </SidebarItem>
                <SidebarItem
                    href="/member/account"
                    label="Account"
                    active={$page.url.pathname === "/member/account"}
                >
                    <svelte:fragment slot="icon">
                        <UserCircleSolid
                            class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        />
                    </svelte:fragment>
                </SidebarItem>
            </SidebarGroup>
        </SidebarWrapper>
    </Sidebar>

    <!-- Mobile Sub-Header -->
    <div
        class="md:hidden flex items-center justify-between px-6 py-4 bg-secondary-600 dark:bg-neutral-900 text-white shadow-xl mx-4 rounded-b-2xl mb-2 backdrop-blur-md bg-opacity-90 dark:bg-opacity-90"
    >
        <div class="flex items-center gap-3">
            <img src="/logo.png" class="w-10" alt="Logo" />
            <span class="font-bold text-lg">Member Area</span>
        </div>
        <div
            class="text-sm px-3 py-1 bg-white bg-opacity-20 rounded-full font-medium"
        >
            {data?.user?.firstName || "Welcome"}
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 pb-24 md:pb-0 px-4 md:px-0">
        <slot />
    </main>

    <!-- Mobile Bottom Navigation -->
    <BottomNav
        position="fixed"
        classOuter="md:hidden z-50 bg-white border-t border-gray-200 dark:bg-neutral-900 dark:border-neutral-800"
        classInner="grid-cols-5"
        activeClass="text-primary-700 dark:text-primary-600"
    >
        <BottomNavItem href="/member" btnName="Dashboard">
            <ChartPieSolid class="w-6 h-6 mb-1 dark:text-white" />
        </BottomNavItem>
        <BottomNavItem href="/member/calendar" btnName="Calendar">
            <CalendarMonthSolid class="w-6 h-6 mb-1 dark:text-white" />
        </BottomNavItem>
        <BottomNavItem href="/member/plan" btnName="Membership">
            <ClipboardListSolid class="w-6 h-6 mb-1 dark:text-white" />
        </BottomNavItem>
        <BottomNavItem href="/member/paws" btnName="Paws">
            <CashSolid class="w-6 h-6 mb-1 dark:text-white" />
        </BottomNavItem>
        <BottomNavItem href="/member/account" btnName="Account">
            <UserCircleSolid class="w-6 h-6 mb-1 dark:text-white" />
        </BottomNavItem>
    </BottomNav>
</div>
