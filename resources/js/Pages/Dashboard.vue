<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="flex mt-5 justify-center">
            <div class="flex-col">
                <Link :href="route('user.create')" type="button" as="button"
                    class="mb-3 px-4 py-2 bg-gray-800 bg-gray-800 border border-transparent rounded-md text-white">
                Create User</Link>
                <UserTable :items="props.users" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import UserTable from '@/Components/UserTable.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from 'primevue';

const props = defineProps({
    users: {
        type: Object
    }
});

</script>
