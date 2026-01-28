import {type Writable, writable} from "svelte/store";
import {BookingType} from "$lib/_types/BookingType";
import type {Product} from "$lib/_types/Product";
import type LenkradDate from "$lib/_types/LenkradDate";

export interface OrderItem{
    product: Product,
    amount: number,
    customization?: string,
    addons?: any[],
    size?: any,
    price: number,
    name?: string

}
export interface Order {
    name: string,
    email:string,
    phone:string,
    type: BookingType,
    items: OrderItem[],
    delivery?: LenkradDate
    subTotal?: number
}
export const order: Writable<Order|null> = writable(null)