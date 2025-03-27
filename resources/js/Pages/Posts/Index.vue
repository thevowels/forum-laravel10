<template>
    <AppLayout>
        <Container>
            <Link v-if="selectedTopic" :href="route('posts.index')" class="text-indigo-500 hover:text-indigo-700">Back to All Posts</Link>
            <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'"/>
            <p v-if="selectedTopic" class="mt-1 text-gray-600 text-sm">{{selectedTopic.description}}</p>

            <menu class="flex mt-3 space-x-1 overflow-x-auto pb-2 pt-1">
                <li >
                    <Pill
                        :href="route('posts.index', {query: searchForm.query})"
                        :filled="!selectedTopic"
                    >
                        All Posts
                    </Pill>

                </li>
                <li v-for="topic in topics" :key="topic.id"  >
                    <Pill
                        :href="route('posts.index', {'topic': topic.slug, 'query': searchForm.query})"
                        :filled="topic.id === selectedTopic?.id"
                    >
                        {{topic.name}}
                    </Pill>

                </li>
            </menu>
            <form @submit.prevent="search" class="mt-4">
                <div>
                    <InputLabel for="query">Search</InputLabel>
                    <div class="space-x-2 flex mt-1 ">
                        <TextInput v-model="searchForm.query" id="query" class="w-full"/>
                        <SecondaryButton type="submit">Search</SecondaryButton>
                        <DangerButton v-if="searchForm.query" @click="clearSearch">Reset</DangerButton>
                    </div>
                </div>
            </form>
            <ul class="divide-y mt-4">
                <li v-for="post in posts.data" :key="post.id" class="flex justify-between items-baseline flex-col md:flex-row">
                    <Link :href="post.routes.show" class="group px-2 py-4 block">
                        <span class="font-bold text-lg group-hover:text-indigo-500">{{post.title}}</span>
                        <span class="text-sm text-gray-600 block mt-1">{{formattedDate(post)}} by {{post.user.name}}</span>
                    </Link>
                    <Pill :href="route('posts.index', {'topic': post.topic.slug})">
                        {{post.topic.name}}
                    </Pill>
                </li>
            </ul>
            <Pagination :meta="posts.meta" :only="['posts']"/>

        </Container>
    </AppLayout>
</template>
<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";


import Pagination from "@/Components/Pagination.vue";
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {relativeDate} from "@/Utilities/date.js";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";


const props = defineProps(['posts','topics', 'selectedTopic', 'query']);

const searchForm = useForm({
    query: props.query,
    page: 1
});

const page = usePage();
const search = () => searchForm.get(page.url);

const clearSearch = () => {
    searchForm.query = '';
    search();
}

const formattedDate = (post) => relativeDate(post.created_at);

</script>
