<script lang="ts">
    import {Heading, P} from "flowbite-svelte";
import GlasBox from "$lib/components/GlasBox.svelte";

export let data;
let {user} = data
</script>
<GlasBox>
    <Heading tag="h1">Administration</Heading>
    <Heading tag="h2">Welcome, {user?.firstName}</Heading>
    <P>All operations on this page will be logged in your name. Make sure to log out when you are done!</P>
</GlasBox>
