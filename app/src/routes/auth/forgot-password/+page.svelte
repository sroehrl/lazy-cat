<script lang="ts">
    import { Alert, Button, Input, Label } from "flowbite-svelte";
    import { enhance } from "$app/forms";

    export let data;
    export let form;
    let loading = false;

    const onSubmit = async () => {
        loading = true;
    };
</script>

<form
    method="post"
    use:enhance={() => {
        loading = true;
        return async ({ update }) => {
            loading = false;
            await update();
        };
    }}
>
    <div
        class="w-4/5 md:w-1/3 mx-auto border rounded-lg shadow dark:text-white mt-10"
    >
        <div
            class="flex flex-col gap-3 py-3 items-center bg-secondary-600 rounded-t-lg"
        >
            <img src="/logo.png" class="w-36" alt="Lazy-Cat-Lounge-Logo" />
            <h3 class="text-xl leading-7 text-primary-700">Reset Password</h3>
        </div>

        <div
            class="grid gap-3 px-4 py-3 bg-white dark:bg-neutral-800 rounded-b-md bg-clip-padding backdrop-filter backdrop-blur-md bg-opacity-70 dark:bg-opacity-60"
        >
            {#if data?.code}
                <input type="hidden" name="code" value={data.code} />
                <Label class="space-y-2">
                    <span>Email</span>
                    <Input
                        value={data.email}
                        type="email"
                        name="email"
                        placeholder="name@example.com"
                        required
                    />
                </Label>
                <Label class="space-y-2">
                    <span>New Password</span>
                    <Input type="password" name="password" required />
                </Label>
                <Button disabled={loading} type="submit">
                    {#if loading}
                        Sending...
                    {:else}
                        Reset Password
                    {/if}
                </Button>
            {:else if form?.success}
                <Alert color="green">
                    An email with further instructions has been sent to your
                    inbox. (check spam/junk folder as well)
                </Alert>
                <div class="flex justify-center pt-2">
                    <a
                        href="/auth"
                        class="text-sm text-primary-600 font-medium hover:underline"
                        >Back to Login</a
                    >
                </div>
            {:else}
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Enter your email address and we'll send you instructions to
                    reset your password.
                </p>
                <Label class="space-y-2">
                    <span>Email</span>
                    <Input
                        value={form?.email || ""}
                        type="email"
                        name="email"
                        placeholder="name@example.com"
                        required
                    />
                </Label>
                {#if form?.error}
                    <Alert color="red" class="mt-2">
                        {form.error || "An error occurred. Please try again."}
                    </Alert>
                {/if}
                <div class="flex justify-between items-center pt-2">
                    <a
                        href="/auth"
                        class="text-sm text-primary-600 font-medium hover:underline"
                        >Back to Login</a
                    >
                    <Button disabled={loading} type="submit">
                        {#if loading}
                            Sending...
                        {:else}
                            Send Reset Link
                        {/if}
                    </Button>
                </div>
            {/if}
        </div>
    </div>
</form>
