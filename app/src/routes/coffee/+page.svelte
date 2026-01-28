<script lang="ts">
    import {onMount} from "svelte";
    import {products} from "$lib/stores/products";
    import {get} from "$lib/api";
    import GlasBox from "$lib/components/GlasBox.svelte";
    import {Button, Checkbox, Heading, P, Search, Select, Textarea} from "flowbite-svelte";
    import {formatCurrency} from "$lib/utils/currency";
    import type {Product} from "$lib/_types/Product";
    import {order, type OrderItem} from "$lib/stores/order";

    let drinks: any[] = []

    let currentItem: OrderItem = {
        product: {},
        amount: 1,
        size: null,
        customization: '',
        addons: [],
        price: 0
    };

    let searchTerm = '';

    const calculateAddonPriceChange = (product: Product) => {
        let price = product.price
        if(currentItem.size){
            price = currentItem.size.price
        }
        currentItem.addons.forEach(addon => {
            price += addon.addonPrice
        })
        currentItem.price = price
    }

    const addToCart = (product: Product) => {
        currentItem.product = product
        calculateAddonPriceChange(product)
        console.log({currentItem})
        $order = {...$order, items:[...$order.items,{...currentItem}]}
    }

    onMount(async () => {
        if($products.length === 0){
            $products = await get('/products')
        }
        if(!$order){
            $order = {items: []}
        }
        drinks = $products.filter(product => product.type === 'beverage')
            .sort((a, b) => a.name.localeCompare(b.name))
            .map(product => ({...product, amount: 0, }))
    })
</script>
<GlasBox>
    <div class="mb-3">
        <Search placeholder="Search drinks" bind:value={searchTerm} />
    </div>
    <div class="flex flex-wrap gap-3">
        {#each drinks.filter(x => x.name.toLowerCase().includes(searchTerm.toLowerCase()) || x.description.toLowerCase().includes(searchTerm.toLowerCase())) as drink}
            <div class="border rounded w-64 p-3 grid gap-3">
                <Heading tag="h5">{drink.name}</Heading>
                <P>{drink.description}</P>
                {#if drink.paws}
                    <P>Paws: {drink.paws}</P>
                {/if}
                {#if drink.sizes.length > 0}
                    <Select on:change={() => currentItem.price = drink.sizes[0].price} placeholder="Size" required bind:value={currentItem.size}>
                        {#each drink.sizes as size, i}
                            <option selected={i === 0} value={size}>{size.size} ({formatCurrency(size.price, 'USD')})</option>
                        {/each}
                    </Select>
                {:else}
                    <P class="py-2">{formatCurrency(drink.price, 'USD')}</P>
                {/if}
                {#if drink.addons.length > 0}
                    {#each drink.addons as addon, i}
                        <Checkbox bind:group={currentItem.addons} value={addon}>{addon.name} <span class="text-xs">(+{formatCurrency(addon.addonPrice, 'USD')})</span></Checkbox>
                    {/each}
                {/if}
                <Textarea bind:value={currentItem.customization} placeholder="Comment.."/>
                <div class="flex place-items-end justify-end">
                    <Button type="submit" on:click={() => addToCart(drink)} size="xs">Add to order</Button>
                </div>
            </div>
        {/each}
    </div>


</GlasBox>