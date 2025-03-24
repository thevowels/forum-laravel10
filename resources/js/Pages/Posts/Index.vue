<template>
    <AppLayout>
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id" >
                    <Link :href="route('posts.show', post.id)" class="group px-2 py-4 block">
                        <span class="font-bold text-lg group-hover:text-indigo-500">{{post.title}}</span>
                        <span class="text-sm text-gray-600 block mt-1">{{formattedDate(post)}} ago by {{post.user.name}}</span>

                    </Link>
                </li>
            </ul>
            <Pagination :meta="posts.meta"/>

        </Container>
    </AppLayout>
</template>
<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";


import Pagination from "@/Components/Pagination.vue";
import { Link } from "@inertiajs/vue3";
import {computed} from "vue";
import {formatDistance, parseISO} from "date-fns";
import {relativeDate} from "@/Utilities/date.js";


defineProps(['posts']);

const formattedDate = (post) => relativeDate(post.created_at);

</script>
