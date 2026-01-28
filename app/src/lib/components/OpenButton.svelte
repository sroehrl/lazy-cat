<script>
import {Button} from "flowbite-svelte";

export let onClick = () => {}
</script>

<Button {...$$restProps} on:click={onClick}>Open</Button>