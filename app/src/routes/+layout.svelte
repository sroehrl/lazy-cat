<script lang="ts">
    import "../app.postcss";
    import {
        Button,
        DarkMode,
        Dropdown,
        DropdownDivider,
        DropdownItem,
        Footer,
        Indicator,
        Navbar,
        NavBrand,
        NavHamburger,
        NavLi,
        NavUl,
        Toast,
        Tooltip,
    } from "flowbite-svelte";
    import { page } from "$app/stores";
    import { base } from "$app/paths";
    import {
        CameraFotoSolid,
        ChevronDownOutline,
        EnvelopeOutline,
        FacebookSolid,
        GithubSolid,
        PhoneOutline,
        ShoppingCartSolid,
        UserCircleSolid,
    } from "flowbite-svelte-icons";
    import ReusableModal from "$lib/components/ReusableModal.svelte";
    import dayjs from "dayjs";
    import { token } from "$lib/stores/auth";
    import { order } from "$lib/stores/order";
    import { toastStore } from "$lib/utils/toast";
    import { user } from "$lib/stores/user";

    export let data;

    if (data.token) {
        $token = data.token;
        $user = data.user;
    }
</script>

<Toast
    divClass="rounded-lg flex items-center w-full max-w-xs p-4 text-gray-100 {$toastStore.color} shadow dark:text-gray-200 gap-3 fixed top-1 right-1 z-10"
    position="none"
    open={$toastStore.open}>{$toastStore.message}</Toast
>
<Navbar
    class="z-10 text-white bg-secondary-600 dark:bg-neutral-900 bg-opacity-80 md:py-10"
    let:hidden
    let:toggle
>
    <NavHamburger on:click={toggle} />
    <NavBrand>
        <span
            class="self-center whitespace-nowrap text-xl font-semibold dark:text-white pl-3"
        >
            <a href="{base}/">
                <img
                    src="/logo.png"
                    class="w-16 md:w-24"
                    alt="Lazy-Cat-Lounge-Logo"
                />
            </a>
        </span>
    </NavBrand>
    <NavUl {hidden} activeUrl={$page.url.pathname}>
        <NavLi class="cursor-pointer text-2xl"
            >About <ChevronDownOutline class="inline w-3" /></NavLi
        >
        <Dropdown class="w-44 z-20">
            <DropdownItem href="/cats">Our current "residents"</DropdownItem>
            <DropdownItem href="/adoption-info">How adoption works</DropdownItem
            >
            <DropdownItem href="/rules">Rules & Etiquette</DropdownItem>
        </Dropdown>
        <NavLi class="text-2xl" href="{base}/calendar">Book Lounge Visits</NavLi
        >
        <NavLi class="text-2xl" href="{base}/coffee">Cafe Menu</NavLi>
        <NavLi class="text-2xl" href="{base}/plans">Membership</NavLi>
        <NavLi class="text-2xl" href="{base}/faq">FAQ</NavLi>
        <NavLi class="text-2xl" href="{base}/rescues">Meet Our Rescues</NavLi>
    </NavUl>
    <div class="flex">
        <NavUl activeUrl={$page.url.pathname}>
            <NavLi class="cursor-pointer relative" href="{base}/checkout">
                <ShoppingCartSolid />
                {#if $order?.items?.length > 0}
                    <Indicator
                        color="dark"
                        size="lg"
                        border
                        placement="top-right">{$order?.items?.length}</Indicator
                    >
                {/if}
            </NavLi>
            <NavLi class="cursor-pointer">
                <UserCircleSolid />
            </NavLi>
            <Dropdown class="w-44 z-20">
                {#if !data.token}
                    <DropdownItem href="/auth">Sign in</DropdownItem>
                    <DropdownItem href="/auth/register">Register</DropdownItem>
                {:else}
                    {#if data.user.isAdmin}
                        <DropdownItem href="/administration"
                            >Administration</DropdownItem
                        >
                    {/if}
                    <DropdownItem href="/member">Member page</DropdownItem>
                    <DropdownDivider />
                    <DropdownItem
                        ><form method="post" action="?/signOut">
                            <button type="submit">Sign out</button>
                        </form></DropdownItem
                    >
                {/if}
            </Dropdown>
        </NavUl>

        <DarkMode />
    </div>
</Navbar>
<div class="my-5 min-h-[calc(100vh-290px)]">
    <slot />
    <ReusableModal />
</div>

<Footer class="bg-black text-white dark:bg-neutral-900">
    <div class="py-5 px-4 grid gap-3 md:grid-cols-3 items-center">
        <div class="gap-3 place-self-center hidden md:flex">
            <Button
                pill
                class="!p-2"
                target="_blank"
                rel="noreferrer noopener"
                href="https://www.facebook.com/lazycatloungeandcafe/"
            >
                <FacebookSolid class="w-4 h-4" />
            </Button>
            <Tooltip>Facebook</Tooltip>
            <Button
                pill
                class="!p-2"
                target="_blank"
                rel="noreferrer noopener"
                href="https://www.facebook.com/lazycatloungeandcafe/"
            >
                <CameraFotoSolid class="w-4 h-4" />
            </Button>
            <Tooltip>Instagram</Tooltip>
        </div>
        <div class="md:flex gap-3 place-self-center">
            <p>
                <a class="text-primary-600 flex gap-1" href="tel:+15854254202"
                    ><PhoneOutline class="w-3" /> 585.425.4202</a
                >
            </p>
            <p class="hidden md:block">
                <a
                    class="text-primary-600 flex gap-1"
                    href="mailto:lazycatloungeandcafe@gmail.com"
                    ><EnvelopeOutline class="w-3" /> lazycatloungeandcafe@gmail.com</a
                >
            </p>
        </div>
        <div class="place-self-center">
            © {dayjs().format("YYYY")} Lazy Cat Lounge & Cafe. All Rights Reserved.
        </div>
    </div>
</Footer>
