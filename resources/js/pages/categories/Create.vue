<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { dashboard } from '@/routes';

const form = ref({ name: '', status: true });
const errors = ref<Record<string, string | string[]>>({});

const getError = (key: string) => {
    const value = errors.value[key];

    return Array.isArray(value) ? value[0] : value;
};

const submit = () => {
    router.post('/categories', form.value, {
        onError: (validationErrors) => {
            errors.value = validationErrors;
        },
    });
};

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Categories', href: '/categories' },
            { title: 'Create', href: '/categories/create' },
        ],
    },
});
</script>

<template>
    <Head title="Create Category" />

    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Create Category</h1>
            <Button variant="outline" as-child>
                <Link href="/categories"
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
                    placeholder="Category name"
                />
                <p v-if="getError('name')" class="text-sm text-red-500">
                    {{ getError('name') }}
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
                <Button type="submit" class="cursor-pointer">Save</Button>
                <Button variant="outline" as-child
                    ><Link href="/categories">Cancel</Link></Button
                >
            </div>
        </form>
    </div>
</template>
