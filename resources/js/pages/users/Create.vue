<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, UserPlus } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});
const errors = ref<Record<string, string | string[]>>({});

const getError = (key: string) => {
    const value = errors.value[key];

    if (Array.isArray(value)) {
        return value[0];
    }

    return value;
};

const submit = () => {
    router.post('/users', { ...form.value }, {
        onSuccess: () => router.visit('/users'),
        onError: (validationErrors) => {
            errors.value = validationErrors as Record<string, string | string[]>;
        },
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
            {
                title: 'Create',
                href: '/users/create',
            },
        ],
    },
});
</script>

<template>
    <Head title="Create User" />

    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Create User</h1>
            </div>
            <Button variant="outline" as-child>
                <Link href="/users">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Back
                </Link>
            </Button>
        </div>

        <form class="w-full space-y-4 rounded-xl border bg-card p-6 shadow-sm" @submit.prevent="submit">
            <div class="space-y-2">
                <Label for="name">Name</Label>
                <Input id="name" v-model="form.name" placeholder="Full Name" />
                <p v-if="getError('name')" class="text-sm text-red-500">{{ getError('name') }}</p>
            </div>

            <div class="space-y-2">
                <Label for="email">Email</Label>
                <Input id="email" v-model="form.email" type="email" placeholder="name@example.com" />
                <p v-if="getError('email')" class="text-sm text-red-500">{{ getError('email') }}</p>
            </div>

            <div class="space-y-2">
                <Label for="password">Password</Label>
                <Input id="password" v-model="form.password" type="password" placeholder="Password" />
                <p v-if="getError('password')" class="text-sm text-red-500">{{ getError('password') }}</p>
            </div>

            <div class="space-y-2">
                <Label for="password_confirmation">Confirm Password</Label>
                <Input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Confirm Password"
                />
                <p v-if="getError('password_confirmation')" class="text-sm text-red-500">
                    {{ getError('password_confirmation') }}
                </p>
            </div>

            <div class="flex gap-2">
                <Button type="submit" class="cursor-pointer">
                    Save
                </Button>
                <Button variant="outline" as-child>
                    <Link href="/users">
                        Cancel
                    </Link>
                </Button>
            </div>
        </form>
    </div>
</template>
