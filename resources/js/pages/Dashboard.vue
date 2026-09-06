<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Users } from '@lucide/vue';
import { dashboard } from '@/routes';

const props = defineProps<{ total_users: number }>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="grid gap-4 md:grid-cols-1">
            <Link href="/users" class="block rounded-xl border border-sidebar-border/70 bg-card p-6 shadow-sm transition-colors hover:bg-accent/50 dark:border-sidebar-border">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-muted-foreground">Total Users</p>
                        <h2 class="mt-2 text-3xl font-semibold">{{ props.total_users }}</h2>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <Users class="h-6 w-6" />
                    </div>
                </div>
            </Link>
        </div>
    </div>
</template>
