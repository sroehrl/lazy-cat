<script lang="ts">
    import {Alert, Button, Checkbox, Input, Label, Modal, Select} from "flowbite-svelte";
    import {post} from "$lib/api";
    import TermsConditions from "$lib/components/TermsConditions.svelte";
    import dayjs from "dayjs";
    import {token} from "$lib/stores/auth";
    import {goto} from "$app/navigation";

    let loading = false;

    let credentials = {
        email: '',
        password: '',
        firstName: '',
        lastName: '',
        phone: '',
        dateOfBirth: '',
        gender: '0',
        terms: false,
        liability: false,
        age: false
    }
    let error = '';

    let liabilityModal = false;
    let ageRestrictionModal = false;
    let termsModal = false;
    const login = async ( ) => {
        loading = true;
        error = '';
        try{
            const register = await post('/member', credentials)
            $token = register.token;
            localStorage.setItem('token', register.token);
            goto('/member')
        } catch (e) {
            if(e.message === 'Forbidden'){
                error = 'Error: Email already registered or unspecified other error.';
            }
        } finally {
            loading = false;

        }
    }

</script>

<form on:submit|preventDefault={login}>
    <div class="w-4/5 md:w-1/3 mx-auto border rounded-lg shadow dark:text-white">
        <div class="flex flex-col gap-3 py-3 items-center bg-secondary-600 rounded-t-lg">
            <img src="/logo.png" class="w-36" alt="Lazy-Cat-Lounge-Logo">
            <h3 class="text-xl leading-7 text-primary-700">Registration</h3>
        </div>

        <div class="grid gap-3 px-4 py-3 bg-white dark:bg-neutral-800 rounded-b-md bg-clip-padding backdrop-filter backdrop-blur-md bg-opacity-70 dark:bg-opacity-60">
            <Label class="space-y-2">
                <span>Email</span>
                <Input required bind:value={credentials.email} type="email" name="email" placeholder="Email"/>
            </Label>
            <Label class="space-y-2">
                <span>Password</span>
                <Input required bind:value={credentials.password} type="password" name="password" placeholder="****"/>
            </Label>
            <div class="grid md:flex gap-2 ">
                <Label class="space-y-2 w-20">
                    <span>Gender</span>
                    <Select required bind:value={credentials.gender} >
                        <option value="0">Mrs</option>
                        <option value="1">Mr</option>
                        <option value="2">Mx</option>
                    </Select>
                </Label>
                <Label class="space-y-2">
                    <span>First name</span>
                    <Input required bind:value={credentials.firstName} type="text" name="firstName" placeholder="Martha"/>
                </Label>
                <Label class="space-y-2">
                    <span>Last name</span>
                    <Input required bind:value={credentials.lastName} type="text" name="lastName" placeholder="Stuard"/>
                </Label>
            </div>
            <div class="flex gap-2">
                <Label class="space-y-2 flex-1">
                    <span>Phone</span>
                    <Input required bind:value={credentials.phone} type="text" name="phone" placeholder="+1 585 622 222"/>
                </Label>
                <Label class="space-y-2">
                    <span>Date of birth</span>
                    <Input max="{dayjs().subtract(18, 'year').format('YYYY-MM-DD')}" required bind:value={credentials.dateOfBirth} type="date" name="dob" placeholder="08/06/1978"/>
                </Label>
            </div>
            <div class="space-y-2">
                <Checkbox required bind:checked={credentials.terms}>
                    <span>I agree to the <button type="button" on:click={() => termsModal = true} class="text-primary-600">terms and conditions</button></span>
                </Checkbox>
                <Checkbox required bind:checked={credentials.age}>
                    <span>I agree to the <button type="button" on:click={() => ageRestrictionModal = true} class="text-primary-600">age restrictions</button></span>
                </Checkbox>
                <Checkbox required bind:checked={credentials.liability}>
                    <span>I hereby agree to the <button type="button" on:click={() => liabilityModal = true} class="text-primary-600">liability and release waiver</button></span>
                </Checkbox>
            </div>
            {#if error}
                <Alert color="red">
                    {error}
                </Alert>
            {/if}
            <div class="flex justify-end">

                <Button disabled={loading} type="submit">Register</Button>
            </div>
            <p class="text-sm">Already a member? <a href="/auth" class="text-primary-600">Log in</a></p>
        </div>
    </div>
</form>
<Modal title="Minimum ages" bind:open={ageRestrictionModal}>
    GUESTS MUST BE 8 YEARS OLD. This is a liability issue and not up to parental discretion. Infants /toddlers in strollers are not allowed in the cat lounge. PLEASE NOTE: NO REFUNDS will be provided for reservations denied due to falsified age on minors.
</Modal>
<Modal title="Liability and release waiver" bind:open={liabilityModal}>
    I, hereby certify that I am over eighteen (18) years of age and am of sound mind at the time of execution of this “Release, Hold Harmless, and Indemnification Agreement” (hereafter known as the “Release”). I agree to the following: 1) Lazy Cat Lounge & Cafe has available cats to be played with by customers and agrees to allow me to play with such cats in consideration of both the payment of the fees and my execution of this Release and agreeing to be bound by its terms. 2) I am aware of and fully understand the inherent dangers involved in playing with cats, including the risk of death and/or personal injury or damage to myself or my property while participating in such activities or having my property at the site of such activity. I further acknowledge that participation in such activities may not be covered under the insurance of Lazy Cat Lounge & Cafe and that customers of said café may not have any right to file a claim against Lazy Cat Lounge & Cafe's insurance policy. I freely and voluntarily execute this Release with such knowledge and assume full and sole responsibility for the risk of death, personal injury and/or property loss arising from or in any way connected with my participation in the activities provided by Lazy Cat Lounge & Cafe. 3) I agree to abide by all rules and regulations that Lazy Cat Lounge & Cafe may impose regarding the resident cats. I agree to follow all rules and to undertake all activities in a responsible manner. IF I AM UNWILLING OR UNABLE TO FOLLOW ANY RULES, LAZY CAT LOUNGE & CAFE WILL TERMINATE MY PARTICIPATION IN SUCH ACTIVITIES AND I WILL NOT BE ENTITLED TO ANY REFUND OF FEES. 4) I acknowledge that playing with cats may not be supervised, and Lazy Cat Lounge & Cafe staff may not be with me the entire time I am in contact with the cats, but Lazy Cat Lounge & Cafe staff will remain on the premises to monitor the activity of all participants, offer guidance and encouragement, and be able to assist in the event of participant difficulty. 5) I have no physical or emotional issue(s) including, but not limited to, any allergies, which would adversely affect my ability to play with the cats in a safe and appropriate manner. 6) I agree not to engage in any activity that will injure or otherwise hurt the cats in any manner. 7) I hereby release and forever discharge Lazy Cat Lounge & Cafe, their respective agents, owners, employees, and independent contractors, and their respective sureties, insurers, successors, assigns, and legal representatives, from any liability, claim, cause of action, demand, and damages for any injury, death, and damages of any kind or nature whatsoever to myself or my property as a result of engaging in any activity at Lazy Cat Lounge & Cafe. including, but not limited to, playing with the cats, whether such injury, death, or property damage is caused by the intentional or negligent act or omission on the part of (i) any other customer of Lazy Cat Lounge & Cafe, (ii) any employee, agent, owner, or independent contractor of Lazy Cat Lounge & Cafe, or (iii) any other person at Lazy Cat Lounge & Cafe. Furthermore, I agree to pay any and all attorney fees and costs of Lazy Cat Lounge & Cafe, and any of their respective agents, employees, owners, and independent contractors if I bring any action, claim, or demand against Lazy Cat Lounge & Cafe or any of their respective agents, employees, owners, or independent contractors for any reason for which this Release applies. 8) I agree to defend with counsel chosen by the indemnified party, indemnify and hold harmless Lazy Cat Lounge & Cafe, their respective agents, employees, owners and independent contractors, their insurers, successors, assigns, and legal representatives from any liability, claim, cause of action, demand or damages for injury, death, or damages of any kind or nature whatsoever to any person or their property resulting from any actual or claimed intentional or wrongful act or omission by me arising from or as a result of my presence at Lazy Cat Lounge & Cafe. I agree to provide said defense and indemnity regardless of the merit of the claim. 9) I agree to and hereby bind my heirs, executors, assigns, and all other legal representatives by executing this Release. 10) I hereby acknowledge and agree that this release is intended to be construed and interpreted as broad and inclusive as permitted by the laws of New York. If any portion of this Release is found or declared to be invalid or unenforceable, such invalidity shall not affect the remainder of this Release not found to be invalid and the remainder of this Release shall remain in full force and effect. This agreement shall be governed by the laws of the State of New York (without regard to the laws that might be applicable under principles of conflicts of law, and without regard to the jurisdiction in which any action or special proceedings may be instituted), as to all matters, including but not limited to matters of jurisdiction, validity, property rights, construction, effect, and performance. All disputes shall be subject to litigation only within the courts of the State of New York, County of Ontario. BY EXECUTING THIS RELEASE, I ACKNOWLEDGE THAT I HAVE READ THIS RELEASE, UNDERSTAND THE CONTENT HEREOF, HAVE BEEN ADVISED AND HAD THE OPPORTUNITY TO SEEK INDEPENDENT COUNSEL OF MY CHOICE AND CERTIFY THAT I HAVE FREELY AND VOLUNTARILY EXECUTED THIS RELEASE. I ACKNOWLEDGE THAT, BUT FOR THE EXECUTION OF THIS RELEASE AND AGREEING TO BE BOUND BY THE TERMS HEREOF, LAZY CAT LOUNGE & CAFE WOULD NOT AUTHORIZE ME TO PARTICIPATE IN ANY ACTIVITIES AT LAZY CAT LOUNGE & CAFE. You are signing this agreement electronically. You agree that your electronic signature is the equivalent of your manual signature on this agreement. By typing your name, you agree to be legally bound by the terms and conditions of this Agreement. You also agree that no certification authority or other third party verification is necessary to validate your E-signature and that lack of such certification or third party verification will not in any way affect the enforceability of your E-signature or any resulting contract between you and Lazy Cat Lounge & Cafe, LLC.
</Modal>
<TermsConditions bind:open={termsModal}/>