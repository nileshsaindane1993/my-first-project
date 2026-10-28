<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

defineProps<{
    links: PaginationLink[];
    from: number | null;
    to: number | null;
    total: number;
}>();

const displayLabel = (label: string) => {
    if (label.includes('Previous')) {
        return 'Previous';
    }

    if (label.includes('Next')) {
        return 'Next';
    }

    return label;
};
</script>

<template>
    <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
        <p class="text-sm text-muted-foreground">
            Showing {{ from ?? 0 }} to {{ to ?? 0 }} of {{ total }} records
        </p>

        <nav
            v-if="links.length > 3"
            aria-label="Pagination"
            class="flex flex-wrap items-center gap-1"
        >
            <template
                v-for="(link, index) in links"
                :key="`${link.label}-${index}`"
            >
                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-scroll
                    class="rounded-md border px-3 py-1.5 text-sm transition-colors hover:bg-accent"
                    :class="
                        link.active
                            ? 'border-primary bg-primary text-primary-foreground hover:bg-primary/90'
                            : ''
                    "
                    :aria-current="link.active ? 'page' : undefined"
                >
                    {{ displayLabel(link.label) }}
                </Link>
                <span
                    v-else
                    class="cursor-not-allowed rounded-md border px-3 py-1.5 text-sm text-muted-foreground opacity-50"
                    aria-disabled="true"
                >
                    {{ displayLabel(link.label) }}
                </span>
            </template>
        </nav>
    </div>
</template>
