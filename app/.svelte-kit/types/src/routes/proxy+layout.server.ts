// @ts-nocheck
import {browser} from "$app/environment";
import '$lib/i18n/i18n'
import { locale, waitLocale } from 'svelte-i18n'
import type { LayoutServerLoad } from './$types';
import serverApi from "$lib/serverApi";
import {Actions, redirect} from "@sveltejs/kit";


export const load = async ({cookies, url}: Parameters<LayoutServerLoad>[0]) => {
    if (browser) {
        locale.set(window.navigator.language)
    }
    await waitLocale()
    let searchParams: any = {};
    for(const [key, value] of url.searchParams.entries()){
        searchParams[key] = value;
    }
    let user;
    if(cookies.get('token')) {
        user = await serverApi(cookies.get('token')).get('/auth/me')
    }

    return {
        token: cookies.get('token') || null,
        search: searchParams,
        user
    }
}

