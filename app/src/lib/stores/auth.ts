import { writable } from 'svelte/store'



export const token = writable(typeof window !== 'undefined' ? localStorage.token : null)

