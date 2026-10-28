<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { Pencil, Trash2, UserPlus } from '@lucide/vue';
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';

type UserRow = {
    id: number;
    name: string;
    email: string;
    created_at: string | null;
    created_at_formatted?: string | null;
    updated_at: string | null;
    updated_at_formatted?: string | null;
};

type UserPagination = {
    data: UserRow[];
    links: { url: string | null; label: string; active: boolean }[];
    from: number | null;
    to: number | null;
    total: number;
};

const props = defineProps<{ users: UserPagination }>();

const deleteUser = (user: UserRow) => {
    if (!confirm(`Delete ${user.name}?`)) {
        return;
    }

    router.delete(`/users/${user.id}`, {
        onSuccess: () => router.reload({ only: ['users'] }),
    });
};

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
            {
                title: 'Users',
                href: '/users',
            },
        ],
    },
});
</script>

<template>
    <Head title="Users" />

    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Users</h1>
                <p class="text-sm text-muted-foreground">
                    Manage users in your application.
                </p>
            </div>
            <Button as-child>
                <Link href="/users/create">
                    <UserPlus class="mr-2 h-4 w-4" />
                    New User
                </Link>
            </Button>
        </div>

        <div class="space-y-4 rounded-xl border bg-card p-4 shadow-sm">
            <div class="mb-4 overflow-hidden rounded-md border">
                <table class="min-w-full text-sm">
                    <thead class="bg-muted text-left">
                        <tr>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium">Created</th>
                            <th class="px-4 py-3 font-medium">Updated</th>
                            <th class="px-4 py-3 text-right font-medium">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!props.users.data.length">
                            <td
                                colspan="5"
                                class="px-4 py-6 text-center text-muted-foreground"
                            >
                                No users found.
                            </td>
                        </tr>
                        <tr
                            v-for="user in props.users.data"
                            :key="user.id"
                            class="border-t"
                        >
                            <td class="px-4 py-3">{{ user.name }}</td>
                            <td class="px-4 py-3">{{ user.email }}</td>
                            <td class="px-4 py-3">
                                {{ user.created_at_formatted ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ user.updated_at_formatted ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <Button
                                        variant="outline"
                                        size="icon"
                                        as-child
                                    >
                                        <Link :href="`/users/${user.id}/edit`">
                                            <Pencil class="h-4 w-4" />
                                        </Link>
                                    </Button>
                                    <Button
                                        class="cursor-pointer"
                                        variant="destructive"
                                        size="icon"
                                        @click="deleteUser(user)"
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
                :links="props.users.links"
                :from="props.users.from"
                :to="props.users.to"
                :total="props.users.total"
            />
        </div>
    </div>
</template>
