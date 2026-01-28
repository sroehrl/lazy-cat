import {writable} from "svelte/store";
import autoFillStore from "../utils/autoFillStore";
import type {Writable} from "svelte/store";

export const user = writable(typeof window !== 'undefined' && typeof localStorage.user !== 'undefined' ? JSON.parse(localStorage.user) : null )

export const users : Writable<any[]> = writable([], () => autoFillStore(users, '/users'))