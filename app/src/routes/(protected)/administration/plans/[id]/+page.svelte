<script lang="ts">
    export let data;
    let plan = data.plans.find(
        (plan: any) => plan.id === Number(data.params.id),
    );
    console.log({ data });
</script>

<h1>{plan?.name}</h1>
