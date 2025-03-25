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
                    <MarkdownEditor v-model="form.body">
                        <template #toolbar="{editor}" v-if="! isInProduction()">
                            <li>
                                <button @click="autofill"
                                        type="button"
                                        class="px-3 py-2"
                                        title="Autofill">
                                    <i class="ri-article-line"></i>
                                </button>
                            </li>

                        </template>

                    </MarkdownEditor>
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
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import axios from "axios";
import {isInProduction} from "@/Utilities/environment.js";


const form = useForm({
    title:'',
    body:'',
});

const createPost = () => form.post(route('posts.store'));

const autofill = async () => {

    if(isInProduction()) {
        return;
    }
   const response = await  axios.get('/local/post-content');
   form.body = response.data.body;
   form.title = response.data.title;

};
</script>
