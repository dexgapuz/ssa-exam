<template>
    <AuthenticatedLayout>
        <div class="flex mt-5 justify-center items-center">
            <Card style="width: 25rem; overflow: hidden">
                <template #header>
                    <Image :src="props.user.avatar" />
                </template>
                <template #title>
                    <div class="text-center">{{ props.user.fullname }}</div>
                </template>
                <template #subtitle>
                    <div class="text-center">{{ props.user.username }} | {{ props.user.email }}</div>
                </template>
                <template #content>
                    <p class="m-0">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore sed consequuntur error
                        repudiandae numquam deserunt quisquam repellat libero asperiores earum nam nobis, culpa ratione
                        quam
                        perferendis esse, cupiditate neque
                        quas!
                    </p>
                </template>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button, Card, Image } from 'primevue';

const props = defineProps({
    user: {
        type: Object
    }
})

</script>
