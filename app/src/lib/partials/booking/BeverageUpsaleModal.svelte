<script lang="ts">
    import {createEventDispatcher, onMount} from "svelte";
    import {products} from "$lib/stores/products";
    import {get} from "$lib/api";
    import {Button, Checkbox, Heading, Modal, P, Select, Textarea} from "flowbite-svelte";
    import {formatCurrency} from "$lib/utils/currency";
    import type {Product} from "$lib/_types/Product";

    export let open = false;

    const dispatch = createEventDispatcher();

    let productOrder = {
        amount: 1,
        product: {},
        size: null,
        customization: '',
        addons: [],
        price: 0
    }

    const calculateAddonPriceChange = (product: Product) => {
        let price = product.price
        if(productOrder.size){
            price = productOrder.size.price
        }
        productOrder.addons.forEach(addon => {
            price += addon.addonPrice
        })
        productOrder.price = price
    }

    const addProduct = (product: Product) => {
        productOrder.product = product
        calculateAddonPriceChange(product)
        dispatch('selected', {...productOrder});
        productOrder = {
            amount: 1,
            product: {},
            size: null,
            customization: '',
            addons: [],
            price: 0
        }
        open = false
    }

    onMount(async () => {
        if($products.length === 0){
            $products = await get('/products')
        }
    })
</script>
<Modal title="Select Beverage" bind:open>
<div class="flex gap-3 flex-wrap">
    {#each $products.filter(product => product.type === 'beverage') as product}
        <form on:submit|preventDefault={() => addProduct(product)} class="rounded border shadow p-3 min-h-56 space-y-3">
            <Heading tag="h5">{product.name}</Heading>
            <P>{product.description}</P>
            {#if product.sizes.length > 0}
                <Select on:change={() => productOrder.price = product.sizes[0].price} placeholder="Size" required bind:value={productOrder.size}>
                    {#each product.sizes as size, i}
                        <option selected={i === 0} value={size}>{size.size} <span class="text-xs">({formatCurrency(size.price, 'USD')})</span></option>
                    {/each}
                </Select>
            {:else}
                <P class="py-2">{formatCurrency(product.price, 'USD')}</P>
            {/if}
            {#if product.addons.length > 0}
                {#each product.addons as addon, i}
                    <Checkbox on:change={() => calculateAddonPriceChange(product)} bind:group={productOrder.addons} value={addon}>{addon.name} <span class="text-xs">(+{formatCurrency(addon.addonPrice, 'USD')})</span></Checkbox>
                {/each}
            {/if}
            <Textarea bind:value={productOrder.customization} placeholder="Comment.."/>
            <div class="flex justify-between">
                <Button type="submit" disabled={product.sizes.length > 0 && productOrder.size === null}  size="xs">Add to order</Button>
            </div>
        </form>
    {/each}
</div>
</Modal>