import { type Writable, writable } from "svelte/store";
import type {Product} from "$lib/_types/Product";

export const products = writable<Product[]>([])