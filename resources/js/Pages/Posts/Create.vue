<template>
    <AppLayout title="Create a Post">
        <Container>
            <h1 class="text-2xl font-bold">Create a Post</h1>

            <form @submit.prevent="createPost" class="mt-6">

                <div>
                    <InputLabel class="sr-only" for="title">Title</InputLabel>
                    <TextInput id="title" class="w-full" v-model="form.title" placeholder="Give it a great Title"/>
                    <InputError :message="form.errors.title" class="mt-1"/>
                </div>

                <div class="mt-3">
                    <InputLabel class="sr-only" for="body">Body</InputLabel>
                    <TextArea id="body" v-model="form.body" rows="25"/>
                    <InputError :message="form.errors.body" class="mt-1"/>
                </div>

                <div class="mt-3">
                    <PrimaryButton type="submit">
                        Create Post
                    </PrimaryButton>
                </div>
            </form>
        </Container>
    </AppLayout>

</template>


<script setup>

import {useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Container from "@/Components/Container.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import TextArea from "@/Components/TextArea.vue";

const form = useForm({
    title:'',
    body:'',
});

const createPost = () => form.post(route('posts.store'));
</script>
