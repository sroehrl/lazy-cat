<script lang="ts">
    import {Alert, Button, Input, Label} from "flowbite-svelte";
    import {post} from "$lib/api";
    import {token} from "$lib/stores/auth";
    import {goto} from "$app/navigation";

    export let data;
    export let form;

    let loading = false;

    let credentials = {
        email: form?.email || '',
        password: ''
    }
    let error = '';
    const login = async ( ) => {
        loading = true;
        error = '';
        try{
            const login = await post('/auth/authenticate', credentials)
            $token = login.token;
            localStorage.setItem('token', login.token);
            localStorage.setItem('isAdmin', login.admin);
            history.back()
        } catch (e) {
            if(e.message === 'Forbidden'){
                error = 'Incorrect email or password';
            }
        } finally {
            loading = false;
        }
    }
    console.log({data, form})
</script>

<form method="post" >
    <input type="hidden" name="redirect" value={data.search.to || '/'}>
    <div class="w-4/5 md:w-1/3 mx-auto border rounded-lg shadow dark:text-white">
        <div class="flex flex-col gap-3 py-3 items-center bg-secondary-600 rounded-t-lg">
            <img src="/logo.png" class="w-36" alt="Lazy-Cat-Lounge-Logo">
            <h3 class="text-xl leading-7 text-primary-700">Login</h3>
        </div>

        <div class="grid gap-3 px-4 py-3 bg-white dark:bg-neutral-800 rounded-b-md bg-clip-padding backdrop-filter backdrop-blur-md bg-opacity-70 dark:bg-opacity-60">
            <Label class="space-y-2">
                <span>Email</span>
                <Input bind:value={credentials.email} type="email" name="email" placeholder="Email"/>
            </Label>
            <Label class="space-y-2">
                <span>Password</span>
                <Input bind:value={credentials.password} type="password" name="password" placeholder="****"/>
            </Label>
            {#if form?.error}
                <Alert color="red">
                    Wrong credentials
                </Alert>
            {/if}
            <div class="flex justify-between">
                <div class="text-sm">
                    <a href="/auth/forgot-password" class="text-red-500">Forgot your password?</a>
                </div>

                <Button disabled={loading} type="submit">Login</Button>
            </div>
            <p class="text-sm">Not yet registered? <a href="/auth/register" class="text-primary-600">Become a member</a></p>
        </div>
    </div>
</form>