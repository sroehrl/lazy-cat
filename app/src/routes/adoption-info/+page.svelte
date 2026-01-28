<script lang="ts">
    import { Heading, P, List, Li, Card, Button } from "flowbite-svelte";
</script>

<svelte:head>
    <title>Adoption Information | Lazy Cat Lounge & Cafe</title>
    <meta
        name="description"
        content="Learn about our cat adoption process, vetting requirements, and how to give a forever home to our residents."
    />
</svelte:head>

<div class="max-w-4xl mx-auto px-4 py-12 bg-primary-50 dark:bg-primary-900/30">
    <header class="text-center mb-12">
        <Heading tag="h1" class="mb-4">Adoption Information</Heading>
        <P class="text-xl text-gray-500"
            >Find out how to give one of our residents their forever home.</P
        >
    </header>

    <div class="space-y-12">
        <section>
            <Heading tag="h2" class="mb-6 text-2xl font-bold"
                >The Process</Heading
            >
            <P class="mb-4">
                We are proud to partner with several local rescues. Because each
                cat comes from a different organization, the adoption process
                and requirements can vary slightly depending on which feline
                friend has captured your heart.
            </P>
            <Card
                padding="xl"
                class="bg-gray-50 dark:bg-neutral-800 border-none shadow-none"
            >
                <Heading tag="h3" class="mb-4 text-xl font-semibold"
                    >General Steps:</Heading
                >
                <List
                    tag="ol"
                    class="space-y-4 text-gray-700 dark:text-gray-300"
                >
                    <Li
                        ><strong>Application:</strong> Fill out an adoption application
                        for the specific rescue organization.</Li
                    >
                    <Li
                        ><strong>Vetting:</strong> The rescue will review your application
                        and perform their standard vetting process.</Li
                    >
                    <Li
                        ><strong>Staff Support:</strong> Our team at the cafe can
                        provide insights into what individual rescues value most.</Li
                    >
                    <Li
                        ><strong>Approval:</strong> Once approved, you'll be contacted
                        to finalize the adoption.</Li
                    >
                    <Li
                        ><strong>Pick-up:</strong> Agree on a pick-up time (usually
                        scheduled for Sundays).</Li
                    >
                </List>
            </Card>
        </section>

        <section>
            <Heading tag="h2" class="mb-6 text-2xl font-bold"
                >Vetting & Requirements</Heading
            >
            <P class="mb-4">
                The primary goal of our rescue partners is to ensure a safe and
                loving environment for every cat. Common factors considered
                during the vetting process include:
            </P>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Card padding="lg">
                    <Heading tag="h4" class="mb-2 text-lg font-bold"
                        >Living Environment</Heading
                    >
                    <P class="text-gray-600 dark:text-gray-400">
                        Rescues may evaluate if your home is suitable for an
                        indoor cat or if there are risks like busy streets for
                        cats that may go outdoors.
                    </P>
                </Card>
                <Card padding="lg">
                    <Heading tag="h4" class="mb-2 text-lg font-bold"
                        >Pet History</Heading
                    >
                    <P class="text-gray-600 dark:text-gray-400">
                        They will often check if you already have pets and if
                        they are up-to-date with their own veterinary care.
                    </P>
                </Card>
            </div>
        </section>

        <section class="bg-primary-50 dark:bg-primary-900/10 p-8 rounded-3xl">
            <Heading tag="h2" class="mb-6 text-2xl font-bold"
                >Adoption Costs</Heading
            >
            <P class="mb-6">
                When an adoption is granted, a fee is collected to help cover
                the costs invested in the cat's health and well-being. This
                typically includes <strong
                    >fixing (spay/neuter), vaccines, and initial medical checks</strong
                >.
            </P>
            <div
                class="flex items-center justify-between bg-white dark:bg-neutral-800 p-6 rounded-2xl shadow-sm"
            >
                <div>
                    <P class="font-bold text-gray-900 dark:text-white"
                        >Typical Adoption Fee</P
                    >
                    <P class="text-sm text-gray-500"
                        >Goes directly to the rescue organization</P
                    >
                </div>
                <div class="text-3xl font-extrabold text-primary-600">
                    Approx. $200
                </div>
            </div>
        </section>

        <section class="text-center">
            <Heading
                tag="h2"
                class="mb-6 text-2xl font-bold text-gray-900 dark:text-white"
                >Ready to start?</Heading
            >
            <P class="mb-8 max-w-2xl mx-auto">
                Visit us at the cafe to meet our residents in person! Our staff
                is happy to answer any specific questions about the cats and
                their respective rescue partners.
            </P>
            <Button href="/cats" color="primary" size="xl"
                >Go Back to Our Cats</Button
            >
        </section>
    </div>
</div>
