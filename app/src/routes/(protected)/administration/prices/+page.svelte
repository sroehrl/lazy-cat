<script lang="ts">
import GlasBox from "$lib/components/GlasBox.svelte";
import {onMount} from "svelte";
import {products} from "$lib/stores/products";
import {get} from "$lib/api";
import PaginationTable from "$lib/components/PaginationTable.svelte";
import dayjs from "dayjs";
import {formatCurrency} from "$lib/utils/currency";
import {Button, Checkbox, Hr, Input, Label, Modal, P, Select, Textarea} from "flowbite-svelte";
import OpenButton from "$lib/components/OpenButton.svelte";
import type {Product} from "$lib/_types/Product";
import {PlusOutline, TrashBinSolid} from "flowbite-svelte-icons";

let data: any[] = [];
let open = false;
let currentProduct: Product;

const renderValue = (key: string, value: any) => {
    switch (key) {
        case 'addons':
            return value.map((addon: any) => addon.name).join(',<br>');
        case 'sizes':
            return value.map((size: any) => size.size).join(',<br>');
        case 'price':
            return formatCurrency(value, 'USD')
        case 'actions':
            return {component:OpenButton, props:{color:'primary', size:'xs', onClick: () => detailView(value)}};
        case 'id':
        case 'name':
        case 'description':
        case 'type':
        case 'paws':
            return value ? value : 'N/A';
    }
}
const detailView = (id: number) => {
    currentProduct = {...$products.find(x => x.id === id)}
    open = true
    console.log({id})
}

onMount(async () => {
    if($products.length < 1){
        $products = await get('/products');
    }
    data = $products.map(x => ({
        id: x.id, name: x.name, description: x.description, type: x.type,
        paws: x.paws, price: x.price, sizes: x.sizes, addons: x.addons, actions: x.id
    }))
})
</script>
<Modal bind:open title="Product Detail" size="md" autoclose>
    <form on:submit|preventDefault={() => {}}>
        <div class="grid gap-3">
            <div class="flex gap-3">
                <Label class="space-y-2 flex-1">
                    <span>Name</span>
                    <Input bind:value={currentProduct.name} />
                </Label>
                <Label class="space-y-2">
                    <span>Type</span>
                    <Select bind:value={currentProduct.type} items={[{name:'beverage', value:'beverage'}, {name:'booking', value: 'booking'}]} />
                </Label>
            </div>

            <Label class="space-y-2">
                <span>Description</span>
                <Textarea bind:value={currentProduct.description} />
                <P class="mt-2 text-sm text-gray-500 dark:text-gray-400">Make sure to use keywords a customer would search for.</P>
            </Label>
            <div class="flex gap-3">
                <Label class="space-y-2 flex-1">
                    <span>Can be paid with paws?</span>
                    <Checkbox checked={!!currentProduct.paws} on:change={(e) => e.target.checked ? currentProduct.paws = 1 : currentProduct.paws = null}/>
                </Label>
                {#if currentProduct.paws}
                <Label class="space-y-2 flex-1">
                    <span>Required paws</span>
                    <Input type="number" bind:value={currentProduct.paws} />
                </Label>
                {/if}
                <Label class="space-y-2 flex-1">
                    <span>Base price</span>
                    <Input type="number" step="0.01" bind:value={currentProduct.price} />
                </Label>
            </div>
            <Label class="space-y-2 ">
                <span>Has different sizes?</span>
                <Checkbox checked={!!currentProduct.sizes.length > 0} on:change={(e) => e.target.checked ? currentProduct.sizes = [...$products.find(x => x.id === currentProduct.id).sizes, {productId: currentProduct.id}] : currentProduct.sizes = []}/>
            </Label>
            {#if currentProduct.sizes.length > 0}
                <div>
                    {#each currentProduct.sizes as size}
                        <div class="flex gap-3 mt-3 {size.deletedAt?.value ? 'opacity-20' : ''}">
                            <Label class="space-y-2 flex-1">
                                <span>Size</span>
                                <Input  bind:value={size.size} />
                            </Label>
                            <Label class="space-y-2 flex-1">
                                <span>Price</span>
                                <Input type="number" step="0.01" bind:value={size.price} />
                            </Label>
                            <div class="flex items-end">
                                <Button  color="red" on:click={() => size.deletedAt.value = new Date()} pill class="!p-2 mb-1"><TrashBinSolid class="w-4"/></Button>
                            </div>
                        </div>
                    {/each}
                    <div class="flex justify-end mt-5">
                        <Button on:click={() => currentProduct.sizes = [...currentProduct.sizes, {productId: currentProduct.id}]} pill class="!p-2"><PlusOutline class="w-4"/></Button>
                    </div>
                </div>

            {/if}
        </div>
        <Label class="space-y-2 mt-3">
            <span>Has addons?</span>
            <Checkbox checked={!!currentProduct.addons.length > 0} on:change={(e) => e.target.checked ? currentProduct.addons = [...$products.find(x => x.id === currentProduct.id).sizes, {productId: currentProduct.id}] : currentProduct.sizes = []}/>
        </Label>
        {#if currentProduct.addons.length > 0}
            {#each currentProduct.addons as addon}
                <div class="flex gap-3 mt-3 {addon.deletedAt?.value ? 'opacity-20' : ''}">
                    <Label class="space-y-2 flex-1">
                        <span>Addon name</span>
                        <Input bind:value={addon.name} />
                    </Label>
                    <Label class="space-y-2 flex-1">
                        <span>Description</span>
                        <Textarea bind:value={addon.description} />
                    </Label>
                    <Label class="space-y-2">
                        <span>Price</span>
                        <Input type="number" step="0.01" bind:value={addon.addonPrice} />
                    </Label>
                    <div class="flex items-center">
                        <Button color="red" on:click={() => addon.deletedAt.value = new Date()} pill class="!p-2"><TrashBinSolid class="w-4"/></Button>
                    </div>
                </div>
            {/each}
            <div class="flex justify-end">
                <Button on:click={() => currentProduct.addons = [...currentProduct.addons, {productId: currentProduct.id}]} pill class="!p-2"><PlusOutline class="w-4"/></Button>
            </div>

        {/if}

    </form>
    <svelte:fragment slot="footer">
        <div class="w-full flex justify-between">
            <Button color="red" on:click={() => { open = false}}>Delete Product</Button>
            <Button color="primary">Save</Button>
        </div>

    </svelte:fragment>
</Modal>

<GlasBox full>
    <PaginationTable {data} {renderValue} />
    <Button class="mt-5" on:click={() => { currentProduct = {}; open = true}}>add Product</Button>
</GlasBox>