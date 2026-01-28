<script lang="ts">
import GlasBox from "$lib/components/GlasBox.svelte";
import PaginationTable from "$lib/components/PaginationTable.svelte";
import {formatCurrency} from "$lib/utils/currency";
import dayjs from "dayjs";

export let data;
let {paypalPlans, plans} = data;
console.log({paypalPlans, plans})

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
    paypalSync: paypalPlans.plans.find((y) => y.status === 'ACTIVE' && y.product_id === 'cc-sub-' + x.id + '-' + x.createdAt.stamp),
    created: x.createdAt,
    updated: x.updatedAt
}))

const renderValue =  (key: string, value: any) => {
    switch (key) {
        case 'pricePerMonth':
            return formatCurrency(value, 'USD');
        case 'visitationDiscount':
            return (value * 100) + '%';
        case 'created':
        case 'updated':
            return value.value ? dayjs(value.value).format('MM/DD/YYYY') : '';
        default:
            return value ? value : 'no';
    }
}
</script>
<GlasBox full>
    <PaginationTable data={planData} {renderValue} />
</GlasBox>