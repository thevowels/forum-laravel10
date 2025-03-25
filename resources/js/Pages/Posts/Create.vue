<template>
    <AppLayout title="Create a Post">
        <Container>
            <PageHeading>Create a Post</PageHeading>

            <form @submit.prevent="createPost" class="mt-6">

                <div>
                    <InputLabel class="sr-only" for="title">Title</InputLabel>
                    <TextInput id="title" class="w-full" v-model="form.title" placeholder="Give it a great Title"/>
                    <InputError :message="form.errors.title" class="mt-1"/>
                </div>

                <div class="mt-3">
                    <InputLabel  for="topic_id">Select a Topic</InputLabel>
                    <select v-model="form.topic_id" class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option v-for="topic in topics" :key="topic" :value="topic.id">{{topic.name}}</option>
                    </select>
                    <InputError :message="form.errors.topic_id" class="mt-1"/>
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
import PageHeading from "@/Components/PageHeading.vue";


const props = defineProps(['topics'])


const form = useForm({
    title:'',
    topic_id: props.topics[0].id,
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
