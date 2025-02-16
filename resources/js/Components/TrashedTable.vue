<template>
    <DataTable :value="props.items.data" tableStyle="min-width: 50rem" class="mb-3">
        <Column field="id" header="Id"></Column>
        <Column field="firstname" header="Firstname"></Column>
        <Column field="middlename" header="Middlename"></Column>
        <Column field="lastname" header="Lastname"></Column>
        <Column field="username" header="Username"></Column>
        <Column field="email" header="Email"></Column>
        <Column class="w-24 !text-end" header="Action">
            <template #body="{ data }">
                <div class="flex gap-2">
                    <span class="underline cursor-pointer"
                        @click="() => router.patch(route('user.restore', { user: data.id }))">Restore</span>
                    <span class="underline cursor-pointer"
                        @click="() => router.delete(route('user.delete', { user: data.id }))">Delete</span>
                </div>
            </template>
        </Column>
    </DataTable>

    <Pagination :links="props.items.links" />

</template>

<script setup>

import { router } from '@inertiajs/vue3';
import { Column, DataTable } from 'primevue';
import Pagination from './Pagination.vue';

const props = defineProps({
    items: Object,
    default: {}
});

</script>
