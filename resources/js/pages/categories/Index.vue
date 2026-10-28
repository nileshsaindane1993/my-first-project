<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { FolderPlus, Pencil, Trash2 } from '@lucide/vue';
import Pagination from '@/components/Pagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';

type CategoryRow = {
    id: number;
    name: string;
    status: boolean;
    products_count: number;
    created_at_formatted: string | null;
};

type CategoryPagination = {
    data: CategoryRow[];
    links: { url: string | null; label: string; active: boolean }[];
    from: number | null;
    to: number | null;
    total: number;
};

const props = defineProps<{ categories: CategoryPagination }>();

const deleteCategory = (category: CategoryRow) => {
    if (!confirm(`Delete ${category.name}?`)) {
        return;
    }

    router.delete(`/categories/${category.id}`);
};

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Categories', href: '/categories' },
        ],
    },
});
</script>

<template>
    <Head title="Categories" />

    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Categories</h1>
                <p class="text-sm text-muted-foreground">
                    Manage product categories.
                </p>
            </div>
            <Button as-child>
                <Link href="/categories/create">
                    <FolderPlus class="mr-2 h-4 w-4" />
                    New Category
                </Link>
            </Button>
        </div>

        <div class="space-y-4 rounded-xl border bg-card p-4 shadow-sm">
            <div class="overflow-x-auto rounded-md border">
                <table class="min-w-full text-sm">
                    <thead class="bg-muted text-left">
                        <tr>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Products</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Created</th>
                            <th class="px-4 py-3 text-right font-medium">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!props.categories.data.length">
                            <td
                                colspan="5"
                                class="px-4 py-6 text-center text-muted-foreground"
                            >
                                No categories found.
                            </td>
                        </tr>
                        <tr
                            v-for="category in props.categories.data"
                            :key="category.id"
                            class="border-t"
                        >
                            <td class="px-4 py-3">{{ category.name }}</td>
                            <td class="px-4 py-3">
                                {{ category.products_count }}
                            </td>
                            <td class="px-4 py-3">
                                <Badge
                                    :variant="
                                        category.status
                                            ? 'default'
                                            : 'destructive'
                                    "
                                >
                                    {{
                                        category.status ? 'Active' : 'Inactive'
                                    }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3">
                                {{ category.created_at_formatted ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <Button
                                        variant="outline"
                                        size="icon"
                                        as-child
                                    >
                                        <Link
                                            :href="`/categories/${category.id}/edit`"
                                            :aria-label="`Edit ${category.name}`"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </Link>
                                    </Button>
                                    <Button
                                        class="cursor-pointer"
                                        variant="destructive"
                                        size="icon"
                                        :aria-label="`Delete ${category.name}`"
                                        @click="deleteCategory(category)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Pagination
                :links="props.categories.links"
                :from="props.categories.from"
                :to="props.categories.to"
                :total="props.categories.total"
            />
        </div>
    </div>
</template>
