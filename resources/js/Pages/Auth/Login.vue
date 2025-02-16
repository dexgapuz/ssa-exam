<template>
    <GuestLayout>

        <Head title="Log in" />

        <Card>
            <template #title>
                <div class="text-center border-b-4 pb-2">Login</div>
            </template>
            <template #content>
                <form @submit.prevent="submit">
                    <div class="flex flex-col gap-2 mb-5 mt-2">
                        <label class="block">Username</label>
                        <InputText v-model="form.username" :invalid="form.errors.username" type="text" size="large"
                            class="w-[500px]" />
                        <Message size="small" severity="error" v-if="form.errors.username" variant="simple">
                            {{ form.errors.username }}
                        </Message>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="block">Password</label>
                        <InputText v-model="form.password" :invalid="form.errors.password" type="password" size="large"
                            class="w-[500px]" />
                        <Message size="small" severity="error" v-if="form.errors.password" variant="simple">
                            {{ form.errors.password }}
                        </Message>
                    </div>
                    <div class="flex items-center mt-4 justify-end">
                        <Link :href="route('register')" class="underline">Register here</Link>
                        <Button label="LOGIN" class="ms-2" type="submit" />
                    </div>
                </form>
            </template>
        </Card>
    </GuestLayout>
</template>

<script setup>

import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button, Card, InputText, Message } from 'primevue';


defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    username: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
