<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import {ChatBubbleLeftEllipsisIcon, ChatBubbleLeftRightIcon, HandThumbUpIcon,} from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import {router, usePage} from "@inertiajs/vue3";
import axios from "axios";
import TextareaInput from "@/Components/App/TextareaInput.vue";
import IndigoButton from "@/Components/App/IndigoButton.vue";
import {ref} from "vue";
import ReadMoreOrHide from "@/Components/App/ReadMoreOrHide.vue";
import EditDeleteDropdown from "@/Components/App/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/App/PostAttachments.vue";

const user = usePage().props.auth.user

const props = defineProps({
    post: Object,
})

const emit = defineEmits(['editClick', 'attachmentClick'])
const editingComment = ref(null)

function openEditModal() {
    emit('editClick', props.post)
}

function deletePost() {
    if (window.confirm('Are you sure to delete this post?')) {
        router.delete(route('post.destroy', props.post.id), {
            preserveScroll: true
        })
    }
}

function openAttachment(index) {
    emit('attachmentClick', props.post, index);
}

function sendReaction() {
    axios.post(route('post.reaction.store', props.post), {reaction: 'like'})
        .then(({data}) => {
            props.post.current_user_has_reaction = data.current_user_has_reaction
            props.post.reactions_count = data.reactions_count
        })
}

function sendCommentReaction(comment) {
    axios.post(route('post.comment.reaction.store', comment), {reaction: 'like'})
        .then(({data}) => {
            comment.current_user_has_reaction = data.current_user_has_reaction
            comment.reactions_count = data.reactions_count
        })
}

const comment = ref('')

function storeComment() {
    axios.post(route('post.comment.store', props.post.id), {
        comment: comment.value
    }).then(({data}) => {
        comment.value = ''
        props.post.comments.unshift(data)
        props.post.comments_count += 1
    })
}

function updateComment() {
    axios.put(route('post.comment.update', editingComment.value.id), {body: editingComment.value.body})
        .then(({data}) => {
            props.post.comments = props.post.comments.map((c) => {
                if (c.id === data.id) {
                    return data;
                }
                return c;
            })
            editingComment.value = null;
        })
}

function deleteComment(comment) {
    if (!window.confirm('Are you sure to delete this comment?')) {
        return false
    }

    axios.delete(route('post.comment.delete', comment))
        .then(() => {
            props.post.comments = props.post.comments.filter(c => c.id !== comment.id)
            props.post.comments_count -= 1
        })
}

function startCommentEdit(comment) {
    editingComment.value = {
        id: comment.id,
        body: comment.body.replace(/<br\s*\/?>/gi, '\n')
    };
}

function stopCommentEdit() {
    editingComment.value = null;
}

</script>

<template>
    <div class="border p-5 rounded-lg bg-white shadow">
        <div class="flex justify-between  items-center mb-3">
            <PostUserHeader :post="post"/>
            <EditDeleteDropdown :user="post.user" @edit="openEditModal" @delete="deletePost"></EditDeleteDropdown>
        </div>
        <div class="overflow-hidden" v-if="post.body">
            <ReadMoreOrHide :content="post.body"/>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-2 sm:grid-cols-1 gap-3">
            <PostAttachments :attachments="post.attachments" @attachmentClick="openAttachment"/>
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

                <div class="flex gap-3 items-center mb-3">
                    <a href="javascript:void(0)" class="self-start">
                        <img :src="user.avatar_url"
                             class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square"
                             alt="avatar">
                    </a>
                    <div class="flex flex-1">
                        <TextareaInput v-model="comment" placeholder="Enter your comment here..." rows="1"
                                       class="w-full resize-none rounded-r-none max-h-[160px] overflow-auto"/>
                        <IndigoButton @click="storeComment" class="w-[100px] rounded-l-none">Submit</IndigoButton>
                    </div>
                </div>

                <div>
                    <div v-for="comment of post.comments" :key="comment.id">
                        <div class="flex gap-3 items-center mb-3">
                            <a href="javascript:void(0)" class="self-start">
                                <img :src="comment.user.avatar_url"
                                     class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square"
                                     alt="avatar">
                            </a>

                            <div class="w-full flex flex-col">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-lg font-semibold">
                                        <a href="javascript:void(0)" class="hover:underline">{{ comment.user.name }}</a>
                                    </h4>

                                    <small class="text-xs text-gray-400">{{ comment.created_at }}</small>
                                </div>

                                <div v-if="editingComment?.id === comment.id">

                                    <TextareaInput v-model="editingComment.body"
                                                   placeholder="Enter your comment here..." rows="1"
                                                   class="w-full resize-none max-h-[160px] overflow-auto"/>
                                    <div class="flex gap-3 justify-end">
                                        <IndigoButton @click="updateComment()" class="w-[100px]">Update
                                        </IndigoButton>
                                        <IndigoButton @click="stopCommentEdit"
                                                      class="w-[100px] bg-red-600 hover:bg-red-500">Cancel
                                        </IndigoButton>
                                    </div>
                                </div>
                                <ReadMoreOrHide v-else :content="comment.body"/>
                                <div class="mt-1 flex gap-2">

                                    <button  @click="sendCommentReaction(comment)"
                                             class="flex items-center text-xs text-indigo-500 hover:bg-indigo-100 rounded-lg p-1"
                                             :class="comment.current_user_has_reaction ? 'bg-indigo-100': ''">
                                        {{ comment.reactions_count }}
                                        <HandThumbUpIcon class="w-4 h-4 mx-1"/>
                                        {{ comment.current_user_has_reaction ? 'Unlike' : 'Like'}}
                                    </button>
                                    <button class="flex items-center text-xs text-underline text-indigo-500 hover:bg-indigo-100 rounded-lg p-1">
                                        <ChatBubbleLeftEllipsisIcon class="w-4 h-4 mr-2"/>
                                        Reply
                                    </button>
                                </div>

                            </div>
                            <EditDeleteDropdown :user="comment.user" @edit="startCommentEdit(comment)"
                                                @delete="deleteComment(comment)"></EditDeleteDropdown>
                        </div>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>
        <div class="flex flex-1 mt-2">
            <TextareaInput placeholder="Enter your comment here..." rows="1"
                           class="w-full resize-none rounded-r-none max-h-[160px] overflow-auto"/>
            <IndigoButton @click="storeComment" class="w-[100px] rounded-l-none">Submit</IndigoButton>
        </div>
    </div>
</template>

