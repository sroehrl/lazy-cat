<script lang="ts">
import {Button} from "flowbite-svelte";
import {page} from "$app/stores";
import {onMount} from "svelte";
import {goto} from "$app/navigation";

export let data;
const adminMenu = [
    {name: 'Members', href: '/administration/members'},
    {name: 'Residents', href: '/administration/residents'},
    // {name: 'Calendar', href: '/administration/calendar'},
    {name: 'Giftcards', href: '/administration/giftcards'},
    {name: 'Plans', href: '/administration/plans'},
    {name: 'Prices', href: '/administration/prices'},
    {name: 'Content', href: '/administration/content'},
    {name: 'Hours', href: '/administration/hours'},
]
onMount(async() => {

    if(!data.user.isAdmin){
        await goto('/')
    }
})
</script>

<div class="flex gap-3 ">
    <div class="w-64 bg-secondary-900 border-r grid gap-2 items-center place-self-start">
        {#each adminMenu as menuItem}
            <Button class="text-white" color="{$page.url.pathname.endsWith(menuItem.href) ? 'primary' : 'transparent'}" href={menuItem.href}>{menuItem.name}</Button>
        {/each}
    </div>
    <div class="content flex-1 pr-3">
        <slot />
    </div>
</div>