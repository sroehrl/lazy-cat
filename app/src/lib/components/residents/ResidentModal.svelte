<script lang="ts">
    import {
        Modal,
        Label,
        Input,
        Select,
        Textarea,
        Button,
        Helper,
    } from "flowbite-svelte";
    import type { Resident } from "$lib/_types/Resident";
    import { createEventDispatcher, onMount } from "svelte";
    import dayjs from "dayjs";

    export let open: boolean = false;
    export let resident: Partial<Resident> = {};

    const dispatch = createEventDispatcher();

    let name = "";
    let gender: "male" | "female" = "female";
    let breed = "";
    let color = "";
    let description = "";
    let status: "available" | "pending" | "adopted" = "available";
    let bornAt = "";
    let image = "";

    let genders = [
        { value: "male", name: "Male" },
        { value: "female", name: "Female" },
    ];

    let statuses = [
        { value: "available", name: "Available" },
        { value: "pending", name: "Pending" },
        { value: "adopted", name: "Adopted" },
    ];

    // Source image for cropping
    let sourceImage: string | null = null;
    let canvas: HTMLCanvasElement;
    let ctx: CanvasRenderingContext2D | null;
    let cropX = 0;
    let cropY = 0;
    let cropSize = 0;
    let dragging = false;
    let startX = 0;
    let startY = 0;
    let imgElement: HTMLImageElement | null = null;

    function initForm(data: Partial<Resident>) {
        name = data.name || "";
        gender = (data.gender as any) || "female";
        breed = data.breed || "";
        color = data.color || "";
        description = data.description || "";
        status = (data.status as any) || "available";
        if (data.bornAt) {
            const dateValue =
                typeof data.bornAt === "string"
                    ? data.bornAt
                    : (data.bornAt as any).value;
            bornAt = dateValue ? dayjs(dateValue).format("YYYY-MM-DD") : "";
        } else {
            bornAt = "";
        }
        image = data.image || "";
        sourceImage = null;
    }

    let lastId: number | undefined | null = -1;
    let lastOpen = false;
    $: if (open && (resident.id !== lastId || !lastOpen)) {
        initForm(resident);
        lastId = resident.id;
        lastOpen = true;
    }
    $: if (!open) {
        lastOpen = false;
        lastId = -1;
    }

    async function handleFileChange(event: Event) {
        const input = event.target as HTMLInputElement;
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                sourceImage = e.target?.result as string;
                initCropper(sourceImage);
            };
            reader.readAsDataURL(file);
        }
    }

    function initCropper(src: string) {
        imgElement = new Image();
        imgElement.onload = () => {
            const minDim = Math.min(imgElement!.width, imgElement!.height);
            cropSize = minDim;
            cropX = (imgElement!.width - cropSize) / 2;
            cropY = (imgElement!.height - cropSize) / 2;
            drawCropper();
        };
        imgElement.src = src;
    }

    function drawCropper() {
        if (!canvas || !imgElement) return;
        ctx = canvas.getContext("2d");
        if (!ctx) return;

        // Scale canvas to preview size but maintain aspect ratio
        const displayWidth = 400;
        const scale = displayWidth / imgElement.width;
        canvas.width = displayWidth;
        canvas.height = imgElement.height * scale;

        ctx.drawImage(imgElement, 0, 0, canvas.width, canvas.height);

        // Darken overlay
        ctx.fillStyle = "rgba(0, 0, 0, 0.5)";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Clear crop area
        const scX = cropX * scale;
        const scY = cropY * scale;
        const scSize = cropSize * scale;
        ctx.clearRect(scX, scY, scSize, scSize);
        ctx.drawImage(
            imgElement,
            cropX,
            cropY,
            cropSize,
            cropSize,
            scX,
            scY,
            scSize,
            scSize,
        );

        // Border
        ctx.strokeStyle = "white";
        ctx.lineWidth = 2;
        ctx.strokeRect(scX, scY, scSize, scSize);
    }

    function handleMouseDown(e: MouseEvent) {
        dragging = true;
        startX = e.offsetX;
        startY = e.offsetY;
    }

    function handleMouseMove(e: MouseEvent) {
        if (!dragging || !imgElement) return;
        const scale = canvas.width / imgElement.width;
        const dx = (e.offsetX - startX) / scale;
        const dy = (e.offsetY - startY) / scale;

        cropX = Math.max(0, Math.min(imgElement.width - cropSize, cropX + dx));
        cropY = Math.max(0, Math.min(imgElement.height - cropSize, cropY + dy));

        startX = e.offsetX;
        startY = e.offsetY;
        drawCropper();
    }

    function handleMouseUp() {
        dragging = false;
    }

    function applyCrop() {
        if (!imgElement) return;
        const resultCanvas = document.createElement("canvas");
        const maxSize = 800;
        resultCanvas.width = maxSize;
        resultCanvas.height = maxSize;
        const resCtx = resultCanvas.getContext("2d");
        resCtx?.drawImage(
            imgElement,
            cropX,
            cropY,
            cropSize,
            cropSize,
            0,
            0,
            maxSize,
            maxSize,
        );
        image = resultCanvas.toDataURL("image/jpeg", 0.8);
        console.log({ image });
        sourceImage = null; // Close cropper view
    }

    function save() {
        const payload = {
            ...resident,
            name,
            gender,
            breed,
            color,
            description,
            status,
            bornAt,
            image,
        };
        dispatch("save", payload);
    }
</script>

<Modal
    title={resident.id ? "Edit Resident" : "Add Resident"}
    bind:open
    size="md"
>
    <div class="space-y-6 mb-4">
        <div class="space-y-2">
            <Label for="name">Name*</Label>
            <Input
                id="name"
                bind:value={name}
                placeholder="Whiskers"
                required
            />
        </div>
        <div class="grid gap-3 grid-cols-2">
            <div class="space-y-2">
                <Label for="gender">Gender*</Label>
                <Select id="gender" items={genders} bind:value={gender} />
            </div>
            <div class="space-y-2">
                <Label for="status">Status*</Label>
                <Select id="status" items={statuses} bind:value={status} />
            </div>
        </div>
        <div class="grid gap-3 grid-cols-2">
            <div class="space-y-2">
                <Label for="breed">Breed</Label>
                <Input id="breed" bind:value={breed} placeholder="Siamese" />
            </div>
            <div class="space-y-2">
                <Label for="color">Color</Label>
                <Input id="color" bind:value={color} placeholder="White/Grey" />
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <Label for="bornAt">Date of Birth</Label>
            <Input id="bornAt" type="date" bind:value={bornAt} />
        </div>
        <div class="flex flex-col gap-2">
            <Label for="description"
                >Description* ({description.length}/255)</Label
            >
            <Textarea
                id="description"
                bind:value={description}
                rows="3"
                maxlength="255"
                required
            />
            {#if description.length > 255}
                <Helper color="red">Description is too long!</Helper>
            {/if}
        </div>
        <div class="flex flex-col gap-2">
            <Label for="image">Image</Label>
            {#if sourceImage}
                <div
                    class="relative bg-black rounded overflow-hidden cursor-move"
                >
                    <canvas
                        bind:this={canvas}
                        on:mousedown={handleMouseDown}
                        on:mousemove={handleMouseMove}
                        on:mouseup={handleMouseUp}
                        on:mouseleave={handleMouseUp}
                    ></canvas>
                    <div class="absolute bottom-2 right-2 flex gap-2">
                        <Button
                            size="xs"
                            color="light"
                            on:click={() => (sourceImage = null)}>Cancel</Button
                        >
                        <Button size="xs" on:click={applyCrop}
                            >Crop & Use</Button
                        >
                    </div>
                </div>
                <Helper>Drag to move the crop area.</Helper>
            {:else}
                <Input
                    id="image"
                    type="file"
                    accept="image/*"
                    on:change={handleFileChange}
                />
                {#if image}
                    <div class="relative inline-block mt-2">
                        <img
                            src={image}
                            alt="Preview"
                            class="h-32 w-32 object-cover rounded"
                        />
                        <Button
                            size="xs"
                            color="red"
                            class="absolute -top-2 -right-2 px-2"
                            on:click={() => (image = "")}>×</Button
                        >
                    </div>
                {/if}
            {/if}
        </div>
    </div>
    <svelte:fragment slot="footer">
        <Button on:click={save}>{resident.id ? "Update" : "Create"}</Button>
        <Button color="alternative" on:click={() => (open = false)}
            >Cancel</Button
        >
    </svelte:fragment>
</Modal>

<style>
    canvas {
        display: block;
        max-width: 100%;
    }
</style>
