<template>
    <AuthenticatedLayout>
        <div class="flex mt-5 justify-center items-center">
            <Card>
                <template #title>
                    <div class="text-center border-b-4 pb-2">{{ isEdit ? 'Edit User' : 'Create User' }}</div>
                </template>
                <template #content>
                    <form enctype="multipart/form-data" @submit.prevent="submit">
                        <div class="flex flex-col gap-2 mb-5 mt-2">
                            <label class="block">Prefix</label>
                            <Select v-model="form.prefixname" default-value="Mr." :options="['Mr.', 'Ms.', 'Mrs.']"
                                class="w-[500px]" />
                            <Message size="small" severity="error" v-if="form.errors.prefixname" variant="simple">
                                {{ form.errors.prefixname }}
                            </Message>
                        </div>
                        <div class="flex flex-col gap-2 mb-5 mt-2">
                            <label class="block">Firstname</label>
                            <InputText v-model="form.firstname" :invalid="form.errors.firstname" type="text"
                                size="large" />
                            <Message size="small" severity="error" v-if="form.errors.firstname" variant="simple">
                                {{ form.errors.firstname }}
                            </Message>
                        </div>
                        <div class="flex flex-col gap-2 mb-5 mt-2">
                            <label class="block">Middlename</label>
                            <InputText v-model="form.middlename" :invalid="form.errors.middlename" type="text"
                                size="large" />
                            <Message size="small" severity="error" v-if="form.errors.middlename" variant="simple">
                                {{ form.errors.middlename }}
                            </Message>
                        </div>
                        <div class="flex flex-col gap-2 mb-5 mt-2">
                            <label class="block">Lastname</label>
                            <InputText v-model="form.lastname" :invalid="form.errors.lastname" type="text"
                                size="large" />
                            <Message size="small" severity="error" v-if="form.errors.lastname" variant="simple">
                                {{ form.errors.lastname }}
                            </Message>
                        </div>
                        <div class="flex flex-col gap-2 mb-5 mt-2">
                            <label class="block">Suffix</label>
                            <InputText v-model="form.suffixname" :invalid="form.errors.suffixname" type="text"
                                size="large" />
                            <Message size="small" severity="error" v-if="form.errors.suffixname" variant="simple">
                                {{ form.errors.suffixname }}
                            </Message>
                        </div>
                        <div class="flex flex-col gap-2 mb-5 mt-2">
                            <label class="block">Email</label>
                            <InputText v-model="form.email" :invalid="form.errors.email" type="text" size="large" />
                            <Message size="small" severity="error" v-if="form.errors.email" variant="simple">
                                {{ form.errors.email }}
                            </Message>
                        </div>
                        <div class="flex flex-col gap-2 mb-5 mt-2">
                            <label class="block">Username</label>
                            <InputText v-model="form.username" :invalid="form.errors.username" type="text"
                                size="large" />
                            <Message size="small" severity="error" v-if="form.errors.username" variant="simple">
                                {{ form.errors.username }}
                            </Message>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="block">Password</label>
                            <InputText v-model="form.password" :invalid="form.errors.password" type="password"
                                size="large" />
                            <Message size="small" severity="error" v-if="form.errors.password" variant="simple">
                                {{ form.errors.password }}
                            </Message>
                        </div>
                        <div class="flex flex-col gap-2 mb-5 mt-2">
                            <label class="block">Photo</label>
                            <InputText @input="form.photo = $event.target.files[0]" :invalid="form.errors.photo"
                                type="file" size="large" />
                            <Message size="small" severity="error" v-if="form.errors.photo" variant="simple">
                                {{ form.errors.photo }}
                            </Message>
                        </div>
                        <div class="flex items-center mt-4 justify-end">
                            <Button label="SUBMIT" class="ms-2" type="submit" />
                        </div>
                    </form>
                </template>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Card, Button, Message, InputText, Select } from 'primevue';
import { onMounted } from 'vue';

const props = defineProps({
    isEdit: {
        type: Boolean,
        required: true
    },
    user: {
        type: Object,
        default: {}
    }
})

const form = useForm({
    prefixname: null,
    firstname: null,
    middlename: null,
    lastname: null,
    suffixname: null,
    email: null,
    username: null,
    password: null,
    photo: null
});

onMounted(() => {
    if (props.isEdit) {
        form.prefixname = props.user.prefixname;
        form.firstname = props.user.firstname;
        form.middlename = props.user.middlename;
        form.lastname = props.user.lastname;
        form.suffixname = props.user.suffixname;
        form.email = props.user.email;
        form.username = props.user.username;
    }
});

const submit = () => {
    const url = props.isEdit ? route('user.update', { user: props.user.id }) : route('user.store');

    form.post(url);
};

</script>
