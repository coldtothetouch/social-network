<script setup>
import {ChatBubbleLeftRightIcon, HandThumbUpIcon,} from '@heroicons/vue/24/outline'
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import EditDeleteDropdown from "@/Components/App/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/App/PostAttachments.vue";
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import ReadMoreOrHide from "@/Components/App/ReadMoreOrHide.vue";
import CommentList from "@/Components/App/CommentList.vue";
import {router} from "@inertiajs/vue3";
import axios from "axios";
import {computed} from "vue";
import PreviewUrl from "@/Components/App/PreviewUrl.vue";

const props = defineProps({
    post: Object,
})

const emit = defineEmits(['editClick', 'attachmentClick'])

function openEditModal() {
    emit('editClick', props.post)
}

function deletePost() {
    if (window.confirm('Are you sure to delete this post?')) {
        router.delete(route('posts.destroy', props.post.id), {
            preserveScroll: true
        })
    }
}

function openAttachment(index) {
    emit('attachmentClick', props.post, index);
}

function sendReaction() {
    axios.post(route('reactions.posts.store', props.post), {reaction: 'like'})
        .then(({data}) => {
            props.post.current_user_has_reaction = data.current_user_has_reaction
            props.post.reactions_count = data.reactions_count
        })
}

const postBody = computed(() => props.post.body.replace(
    /(?:(\s+)|<p>)((#\w+)(?![^<]*<\/a>))/,
    (match, group1, group2) => `${group1 || ''}<a href="/search/${encodeURIComponent(group2)}">${group2}</a>`
))

</script>

<template>
    <div class="border p-5 rounded-lg bg-white shadow">
        <div class="flex justify-between  items-center mb-3">
            <PostUserHeader :post="post"/>
            <EditDeleteDropdown :post="post" @edit="openEditModal" @delete="deletePost()"/>
        </div>
        <div class="overflow-hidden" v-if="post.body || post.preview_url">
            <ReadMoreOrHide :content="postBody"/>
            <PreviewUrl :preview="post.preview" :url="post.preview_url"/>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-2 sm:grid-cols-1 gap-3">
            <PostAttachments v-if="post.attachments"
                             :attachments="post.attachments"
                             @attachmentClick="openAttachment"
            />
        </div>

        <Disclosure v-slot="{ open }">
            <div class="flex gap-5 justify-between mt-5 text-gray-600">
                <button @click="sendReaction"
                        class="flex flex-1 gap-1 justify-center items-center border py-5 px-10 rounded-full hover:bg-gray-100"
                        :class="post.current_user_has_reaction ? 'text-indigo-500 bg-indigo-100 hover:bg-indigo-200' : ''">
                    <HandThumbUpIcon class="h-7 w-7 text-gray-400"
                                     :class="post.current_user_has_reaction ? 'text-indigo-500' : ''"/>
                    <span class="mr-2">{{ post.reactions_count }}</span>
                    {{ post.current_user_has_reaction ? 'Unlike' : 'Like' }}
                </button>
                <DisclosureButton
                    class="flex flex-1 justify-center items-center gap-1 border py-5 px-10 rounded-full hover:bg-gray-100"
                >
                    <ChatBubbleLeftRightIcon class="h-7 w-7 text-gray-400"/>
                    <span class="mr-2">{{ post.comments_count }}</span>
                    Comment
                </DisclosureButton>
            </div>
            <DisclosurePanel class="px-4 pb-2 pt-4 text-sm text-gray-500">
                <CommentList :post="post" :data="{comments: post.comments}"/>
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>

