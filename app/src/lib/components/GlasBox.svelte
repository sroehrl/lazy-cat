<script lang="ts">
    export let full = false;

    $: boxed = full ? '' : 'mx-3 md:w-4/5 lg:w-3/4 md:mx-auto';
</script>

<div class="bg-white dark:bg-neutral-800 rounded-md bg-clip-padding backdrop-filter backdrop-blur-md bg-opacity-80 dark:bg-opacity-60 border p-3 {boxed}">
    <slot/>
</div>