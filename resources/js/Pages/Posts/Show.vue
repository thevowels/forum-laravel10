<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{post.title}}</h1>
            <span class="text-sm text-gray-600 block mt-1">{{formattedDate}} ago by {{post.user.name}}</span>
            <article class="mt-6">
                <pre class="whitespace-pre-wrap font-sans">
                    {{post.body}}
                </pre>
            </article>
            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>

                <form v-if="$page.props.auth.user" @submit.prevent="addComment" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <TextArea rows="4" id = "body" v-model="commentForm.body"/>
                        <InputError :message="commentForm.errors.body" />
                    </div>
                    <PrimaryButton type="submit" class="mt-3" :disabled="commentForm.processing">Add Comment</PrimaryButton>
                </form>

                <ul class="divide-y">

                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                        <Comment :comment="comment"></Comment>
                    </li>
                </ul>
                <Pagination :meta="comments.meta" :only="['comments']"/>
            </div>
        </Container>
    </AppLayout>

</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import {formatDistance, parseISO} from "date-fns"
import {computed} from "vue";
import Container from "@/Components/Container.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {relativeDate} from "@/Utilities/date.js";
import Pagination from "@/Components/Pagination.vue";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
const formattedDate = computed(() => relativeDate(props.post.created_at))

const props = defineProps(['post','comments']);

const commentForm = useForm({
    'body':''
});

const addComment = () => {
    commentForm.post(route('posts.comments.store', props.post.id),
        {
                    preserveScroll: true,
                    onSuccess: () => commentForm.reset()
            });
}
</script>
