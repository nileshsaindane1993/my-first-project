<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { dashboard } from '@/routes';

type CategoryOption = { id: number; name: string };
const props = defineProps<{ categories: CategoryOption[] }>();
const form = ref({
    name: '',
    category_id: 0,
    price: '',
    description: '',
    status: true,
});
const errors = ref<Record<string, string | string[]>>({});

const getError = (key: string) => {
    const value = errors.value[key];

    return Array.isArray(value) ? value[0] : value;
};

const submit = () => {
    router.post('/products', form.value, {
        onError: (validationErrors) => {
            errors.value = validationErrors;
        },
    });
};

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Products', href: '/products' },
            { title: 'Create', href: '/products/create' },
        ],
    },
});
</script>

<template>
    <Head title="Create Product" />

    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Create Product</h1>
            <Button variant="outline" as-child>
                <Link href="/products"
                    ><ArrowLeft class="mr-2 h-4 w-4" />Back</Link
                >
            </Button>
        </div>

        <form
            class="w-full space-y-4 rounded-xl border bg-card p-6 shadow-sm"
            @submit.prevent="submit"
        >
            <div class="space-y-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    placeholder="Product name"
                />
                <p v-if="getError('name')" class="text-sm text-red-500">
                    {{ getError('name') }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="category_id">Category</Label>
                <select
                    id="category_id"
                    v-model.number="form.category_id"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs"
                >
                    <option :value="0" disabled>Select a category</option>
                    <option
                        v-for="category in props.categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
                <p v-if="getError('category_id')" class="text-sm text-red-500">
                    {{ getError('category_id') }}
                </p>
                <p
                    v-if="!props.categories.length"
                    class="text-sm text-muted-foreground"
                >
                    Create a category before adding a product.
                </p>
            </div>

            <div class="space-y-2">
                <Label for="price">Price</Label>
                <Input
                    id="price"
                    v-model="form.price"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                />
                <p v-if="getError('price')" class="text-sm text-red-500">
                    {{ getError('price') }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="description">Description</Label>
                <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs"
                />
                <p v-if="getError('description')" class="text-sm text-red-500">
                    {{ getError('description') }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="status">Status</Label>
                <div class="flex items-center gap-3">
                    <Switch id="status" v-model="form.status" />
                    <span class="text-sm text-muted-foreground">
                        {{ form.status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <p v-if="getError('status')" class="text-sm text-red-500">
                    {{ getError('status') }}
                </p>
            </div>

            <div class="flex gap-2">
                <Button type="submit" :disabled="!props.categories.length" class="cursor-pointer"
                    >Save</Button
                >
                <Button variant="outline" as-child
                    ><Link href="/products">Cancel</Link></Button
                >
            </div>
        </form>
    </div>
</template>
