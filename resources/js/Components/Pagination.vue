<template>
    <nav class="flex justify-center">
        <Link v-for="link in links" :href="link.url ?? ''" v-html="link.label"
            class="flex items-center justify-center py-2 px-3 text-sm rounded-lg"
            :class="{ 'bg-gray-200': link.active }" />
    </nav>
</template>

<script setup>

import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: {
        type: Array,
        required: true
    }
});

</script>
