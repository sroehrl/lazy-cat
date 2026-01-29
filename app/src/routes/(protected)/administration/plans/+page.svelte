<script lang="ts">
    import { Button } from "flowbite-svelte";
    import GlasBox from "$lib/components/GlasBox.svelte";
    import PaginationTable from "$lib/components/PaginationTable.svelte";
    import { formatCurrency } from "$lib/utils/currency";
    import dayjs from "dayjs";

    export let data;
    let { paypalPlans, plans } = data;
    console.log({ paypalPlans, plans });

    const planData = plans.map((x) => ({
        id: x.id,
        name: x.name,
        description: x.description,
        visitationDiscount: x.visitationDiscount,
        pricePerMonth: x.pricePerMonth,
        appliesWeekdays: x.weekdays,
        appliesWeekends: x.weekends,
        guest: x.guest,
        pawsPerMonth: x.pawsPerMonth,
        paypalSync:
            paypalPlans.plans.find(
                (y) =>
                    y.status === "ACTIVE" &&
                    y.product_id ===
                        "cc-prod-" + x.id + "-" + x.createdAt.stamp,
            )?.id || null,
        created: x.createdAt,
        updated: x.updatedAt,
        action: x.id,
    }));

    const renderValue = (key: string, value: any) => {
        switch (key) {
            case "pricePerMonth":
                return formatCurrency(value, "USD");
            case "visitationDiscount":
                return value * 100 + "%";
            case "created":
            case "updated":
                return value.value
                    ? dayjs(value.value).format("MM/DD/YYYY")
                    : "";
            case "action":
                return `<a href="/administration/plans/${value}">Edit</a>`;
            default:
                return value ? value : "no";
        }
    };
</script>

<div class="mb-6 flex justify-end">
    <Button href="/administration/plans/create">Add Membership Plan</Button>
</div>
<GlasBox full>
    <PaginationTable data={planData} {renderValue} />
</GlasBox>
