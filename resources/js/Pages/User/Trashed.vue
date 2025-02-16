<template>

    <Head title="Trashed" />

    <AuthenticatedLayout>
        <div class="flex mt-5 justify-center">
            <div class="flex-col">
                <TrashedTable :items="props.users" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import TrashedTable from '@/Components/TrashedTable.vue';
import UserTable from '@/Components/UserTable.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    users: {
        type: Object
    }
});

onMounted(() => {
    console.log(props.users);
})

</script>
