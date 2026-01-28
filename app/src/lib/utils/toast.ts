import {writable} from "svelte/store";

export const toastStore = writable({
    message: '',
    color: 'green',
    open: false
})
export const toast = {
    success(message: string): void {
        toastStore.update(x => ({
            message: message,
            color: "bg-green-500",
            open: true
        }))
        close();
    },
    error(message: string): void {
        toastStore.update(x => ({
            message: message,
            color: "bg-red-500",
            open: true
        }))
        close();
    }
}

const close = () => setTimeout(() => toastStore.update((x) => ({...x,open:false})), 2000)