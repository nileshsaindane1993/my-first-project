<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { PackagePlus, Pencil, Trash2 } from '@lucide/vue';
import Pagination from '@/components/Pagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';

type ProductRow = {
    id: number;
    name: string;
    category_name: string;
    price: number;
    description: string;
    status: boolean;
    created_at_formatted: string | null;
};

type ProductPagination = {
    data: ProductRow[];
    links: { url: string | null; label: string; active: boolean }[];
    from: number | null;
    to: number | null;
    total: number;
};

const props = defineProps<{ products: ProductPagination }>();

const deleteProduct = (product: ProductRow) => {
    if (!confirm(`Delete ${product.name}?`)) {
        return;
    }

    router.delete(`/products/${product.id}`);
};

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Products', href: '/products' },
        ],
    },
});
</script>

<template>
    <Head title="Products" />

    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Products</h1>
                <p class="text-sm text-muted-foreground">
                    Manage your product catalog.
                </p>
            </div>
            <Button as-child>
                <Link href="/products/create">
                    <PackagePlus class="mr-2 h-4 w-4" />
                    New Product
                </Link>
            </Button>
        </div>

        <div class="space-y-4 rounded-xl border bg-card p-4 shadow-sm">
            <div class="overflow-x-auto rounded-md border">
                <table class="min-w-full text-sm">
                    <thead class="bg-muted text-left">
                        <tr>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Category</th>
                            <th class="px-4 py-3 font-medium">Price</th>
                            <th class="px-4 py-3 font-medium">Description</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Created</th>
                            <th class="px-4 py-3 text-right font-medium">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!props.products.data.length">
                            <td
                                colspan="7"
                                class="px-4 py-6 text-center text-muted-foreground"
                            >
                                No products found.
                            </td>
                        </tr>
                        <tr
                            v-for="product in props.products.data"
                            :key="product.id"
                            class="border-t"
                        >
                            <td class="px-4 py-3">{{ product.name }}</td>
                            <td class="px-4 py-3">
                                {{ product.category_name }}
                            </td>
                            <td class="px-4 py-3">
                                {{ Number(product.price).toFixed(2) }}
                            </td>
                            <td class="max-w-xs truncate px-4 py-3">
                                {{ product.description }}
                            </td>
                            <td class="px-4 py-3">
                                <Badge
                                    :variant="
                                        product.status
                                            ? 'default'
                                            : 'destructive'
                                    "
                                >
                                    {{ product.status ? 'Active' : 'Inactive' }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3">
                                {{ product.created_at_formatted ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <Button
                                        variant="outline"
                                        size="icon"
                                        as-child
                                    >
                                        <Link
                                            :href="`/products/${product.id}/edit`"
                                            :aria-label="`Edit ${product.name}`"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </Link>
                                    </Button>
                                    <Button
                                        class="cursor-pointer"
                                        variant="destructive"
                                        size="icon"
                                        :aria-label="`Delete ${product.name}`"
                                        @click="deleteProduct(product)"
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
                :links="props.products.links"
                :from="props.products.from"
                :to="props.products.to"
                :total="props.products.total"
            />
        </div>
    </div>
</template>
