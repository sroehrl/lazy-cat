<script lang="ts">
    import { Heading, P, List, Li, Card } from "flowbite-svelte";
    import {
        InfoCircleOutline,
        ExclamationCircleOutline,
        HeartOutline,
    } from "flowbite-svelte-icons";
</script>

<svelte:head>
    <title>Rules & Etiquette | Lazy Cat Lounge & Cafe</title>
    <meta
        name="description"
        content="Important rules and etiquette for visiting our cat lounge to ensure a safe and happy environment for everyone."
    />
</svelte:head>

<div class="max-w-4xl mx-auto px-4 py-12 bg-gray-50 dark:bg-neutral-800">
    <header class="text-center mb-5">
        <Heading tag="h1" class="mb-4">Lounge Rules & Etiquette</Heading>
        <P class="text-xl text-gray-500"
            >To ensure the safety and happiness of our feline residents and
            guests, please follow these guidelines.</P
        >
    </header>

    <div class="grid gap-8 md:grid-cols-2">
        <Card
            padding="lg"
            class="bg-primary-50 dark:bg-primary-900/10 border-none"
        >
            <div class="flex items-center gap-3 mb-4 text-primary-600">
                <InfoCircleOutline class="w-6 h-6" />
                <Heading tag="h3" class="text-xl font-bold"
                    >Age Requirement</Heading
                >
            </div>
            <P class="text-gray-700 dark:text-gray-300">
                For the safety of both the children and our cats, all visitors
                must be <strong>at least 8 years of age</strong> to enter the lounge.
                All minors must be accompanied by an adult.
            </P>
        </Card>

        <Card
            padding="lg"
            class="bg-secondary-50 dark:bg-secondary-900 border-none"
        >
            <div class="flex items-center gap-3 mb-4 text-secondary-600">
                <HeartOutline class="w-6 h-6" />
                <Heading tag="h3" class="text-xl font-bold"
                    >Calm Behavior</Heading
                >
            </div>
            <P class="text-gray-700 dark:text-gray-300">
                Live animals appreciate a calm environment. Please avoid
                running, shouting, or chasing the cats. Quiet voices and gentle
                movements help our residents feel safe.
            </P>
        </Card>

        <Card padding="lg" class="bg-orange-50 dark:bg-orange-700 border-none">
            <div class="flex items-center gap-3 mb-4 text-orange-600">
                <ExclamationCircleOutline class="w-6 h-6" />
                <Heading tag="h3" class="text-xl font-bold"
                    >Respect Boundaries</Heading
                >
            </div>
            <P class="text-gray-700 dark:text-gray-300">
                Remember that cats are living beings and can scratch if they
                feel cornered or overstimulated. Never force interaction or wake
                a sleeping cat.
            </P>
        </Card>

        <Card padding="lg" class="bg-blue-50 dark:bg-blue-900 border-none">
            <div class="flex items-center gap-3 mb-4 text-blue-600">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="3em"
                    height="3em"
                    viewBox="0 0 24 24"
                >
                    <g
                        fill="none"
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                    >
                        <path
                            fill="currentColor"
                            fill-opacity="0"
                            stroke-dasharray="48"
                            stroke-dashoffset="48"
                            d="M17 9v9c0 1.66 -1.34 3 -3 3h-6c-1.66 0 -3 -1.34 -3 -3v-9Z"
                        >
                            <animate
                                fill="freeze"
                                attributeName="fill-opacity"
                                begin="0.8s"
                                dur="0.5s"
                                values="0;1"
                            />
                            <animate
                                fill="freeze"
                                attributeName="stroke-dashoffset"
                                dur="0.6s"
                                values="48;0"
                            />
                        </path>
                        <path
                            stroke-dasharray="14"
                            stroke-dashoffset="14"
                            d="M17 9h3c0.55 0 1 0.45 1 1v3c0 0.55 -0.45 1 -1 1h-3"
                        >
                            <animate
                                fill="freeze"
                                attributeName="stroke-dashoffset"
                                begin="0.6s"
                                dur="0.2s"
                                values="14;0"
                            />
                        </path>
                        <mask id="lineMdCoffeeFilledLoop0">
                            <path
                                stroke="#fff"
                                d="M8 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4M12 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4M16 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4"
                            >
                                <animateMotion
                                    calcMode="linear"
                                    dur="3s"
                                    path="M0 0v-8"
                                    repeatCount="indefinite"
                                />
                            </path>
                        </mask>
                        <rect
                            width="24"
                            height="0"
                            y="7"
                            fill="currentColor"
                            mask="url(#lineMdCoffeeFilledLoop0)"
                        >
                            <animate
                                fill="freeze"
                                attributeName="y"
                                begin="0.8s"
                                dur="0.6s"
                                values="7;2"
                            />
                            <animate
                                fill="freeze"
                                attributeName="height"
                                begin="0.8s"
                                dur="0.6s"
                                values="0;5"
                            />
                        </rect>
                    </g>
                </svg>
                <Heading tag="h3" class="text-xl font-bold"
                    >Hygiene & Safety</Heading
                >
            </div>
            <P class="text-gray-700 dark:text-gray-300">
                Please use the provided hand sanitizer before entering. For the
                safety of the cats,
                <strong>only staff members</strong> are allowed to operate the lounge
                doors.
            </P>
        </Card>
    </div>

    <section class="mt-5 bg-gray-50 dark:bg-neutral-800 p-8 rounded-3xl">
        <header class="text-center mb-8">
            <Heading tag="h2" class="text-2xl font-bold"
                >Lounge Etiquette Checklist</Heading
            >
        </header>
        <List
            tag="ul"
            class="max-w-2xl mx-auto space-y-4 text-gray-700 dark:text-gray-300"
        >
            <Li
                ><strong>Bring your coffee:</strong> You are highly encouraged to
                bring your drinks from the cafe into the lounge!</Li
            >
            <Li
                ><strong>Sanitize:</strong> Use the station near the door before
                and after your visit.</Li
            >
            <Li
                ><strong>Photos:</strong> Feel free to take photos, but please
                ensure the flash is turned <strong>off</strong>.</Li
            >
            <Li
                ><strong>Staff Instructions:</strong> Always follow any additional
                instructions provided by our cafe staff.</Li
            >
        </List>
    </section>

    <div class="mt-16 text-center">
        <P class="italic text-gray-500"
            >Thank you for helping us maintain a safe haven for our rescued
            feline friends!</P
        >
    </div>
</div>
