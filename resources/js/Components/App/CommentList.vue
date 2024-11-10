<script setup>

import TextareaInput from "@/Components/App/TextareaInput.vue";
import {ChatBubbleLeftEllipsisIcon, HandThumbUpIcon} from "@heroicons/vue/24/outline/index.js";
import IndigoButton from "@/Components/App/IndigoButton.vue";
import EditDeleteDropdown from "@/Components/App/EditDeleteDropdown.vue";
import ReadMoreOrHide from "@/Components/App/ReadMoreOrHide.vue";
import {usePage, Link} from "@inertiajs/vue3";
import {ref} from "vue";
import axios from "axios";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";

const user = usePage().props.auth.user

const props = defineProps({
    post: Object,
    data: Object,
    parentComment: {
        type: [Object, null],
        default: null
    },
})

const emit = defineEmits(['commentCreate', 'commentDelete'])

/**
 *  Comment Creation
 */

const comment = ref('')

function storeComment() {
    axios.post(route('posts.comments.store', props.post.id), {
        comment: comment.value,
        parent_id: props.parentComment?.id || null
    }).then(({data}) => {
        if (props.parentComment) {
            props.parentComment.comments_count++
        }
        props.post.comments_count++
        comment.value = ''
        props.data.comments.unshift(data)
        emit('commentCreate', data)
    })
}

function onCommentCreate(comment) {
    if (props.parentComment) {
        props.parentComment.comments_count++
    }
    emit('commentCreate', comment);
}

/**
 *  Comment Deleting
 */

function deleteComment(comment) {
    if (!window.confirm('Are you sure to delete this comment?')) {
        return false
    }

    axios.delete(route('posts.comments.destroy', comment))
        .then(() => {
            const commentIndex = props.data.comments.findIndex(c => c.id === comment.id)
            props.data.comments.splice(commentIndex, 1)

            const childCommentsCount = comment.comments.length

            if (props.parentComment) {
                props.parentComment.comments_count -= childCommentsCount + 1
            }

            props.post.comments_count -= childCommentsCount + 1
            emit('commentDelete', comment)
        })
}


function onCommentDelete(comment) {
    const childCommentsCount = comment.comments.length

    if (props.parentComment) {
        props.parentComment.comments_count -= childCommentsCount + 1
    }

    emit('commentDelete', comment)
}

/**
 *  Comment Editing
 */

const editingComment = ref(null)

function updateComment() {
    axios.put(route('posts.comments.update', editingComment.value.id), {body: editingComment.value.body})
        .then(({data}) => {
            props.data.comments = props.data.comments.map((c) => {
                if (c.id === data.id) {
                    return data;
                }
                return c;
            })
            editingComment.value = null;
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

/**
 *  Comment Reactions
 */

function sendCommentReaction(comment) {
    axios.post(route('reactions.comments.store', comment), {reaction: 'like'})
        .then(({data}) => {
            comment.current_user_has_reaction = data.current_user_has_reaction
            comment.reactions_count = data.reactions_count
        })
}

</script>

<template>
    <div class="flex gap-3 items-center mb-3">
        <Link :href="route('profile.index', user)">
            <img :src="user.avatar_path"
                 class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square"
                 alt="avatar">
        </Link>
        <div class="flex flex-1">
            <TextareaInput v-model="comment" placeholder="Enter your comment here..." rows="1"
                           class="dark:bg-slate-700 w-full resize-none rounded-r-none max-h-[160px] overflow-auto"/>
            <IndigoButton @click="storeComment()" class="w-[100px] rounded-l-none">Submit</IndigoButton>
        </div>
    </div>
    <div>
        <div v-for="comment of props.data.comments" :key="comment.id">

            <div class="flex gap-3 items-center mb-3">
                <Link :href="route('profile.index', comment.user)" class="self-start">
                    <img :src="comment.user.avatar_url"
                         class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square"
                         alt="avatar">
                </Link>

                <div class="dark:text-white w-full flex flex-col">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold">
                            <Link :href="route('profile.index', comment.user)" class="hover:underline">
                                {{ comment.user.name }}
                            </Link>
                        </h4>

                        <small class="text-xs text-gray-400">{{ comment.created_at }}</small>
                    </div>

                    <div v-if="editingComment?.id === comment.id">

                        <TextareaInput v-model="editingComment.body"
                                       placeholder="Enter your comment here..." rows="1"
                                       class="w-full resize-none max-h-[160px] overflow-auto"/>
                        <div class="flex gap-3 justify-end">
                            <IndigoButton @click="updateComment" class="w-[100px]">Update
                            </IndigoButton>
                            <IndigoButton @click="stopCommentEdit"
                                          class="w-[100px] bg-red-600 hover:bg-red-500">Cancel
                            </IndigoButton>
                        </div>
                    </div>
                    <ReadMoreOrHide v-else :content="comment.body"/>
                    <Disclosure>
                        <div class="mt-1 flex gap-2">
                            <button @click="sendCommentReaction(comment)"
                                    class="dark:text-gray-400 dark:hover:bg-slate-700 flex items-center text-xs text-indigo-500 hover:bg-indigo-100 rounded-lg p-1"
                                    :class="comment.current_user_has_reaction ? 'dark:bg-slate-700 bg-indigo-100': ''">
                                {{ comment.reactions_count }}
                                <HandThumbUpIcon class="w-4 h-4 mx-1"/>
                                {{ comment.current_user_has_reaction ? 'unlike' : 'like' }}
                            </button>
                            <DisclosureButton>
                                <button
                                    class="dark:text-gray-400 dark:hover:bg-slate-700 flex items-center text-xs text-underline text-indigo-500 hover:bg-indigo-100 rounded-lg p-1">
                                    {{ comment.comments_count }}
                                    <ChatBubbleLeftEllipsisIcon class="w-4 h-4 mx-1"/>
                                    comments
                                </button>
                            </DisclosureButton>
                        </div>
                        <DisclosurePanel>
                            <CommentList @comment-create="onCommentCreate" @comment-delete="onCommentDelete"
                                         :post="props.post" :data="{comments:  comment.comments}"
                                         :parent-comment="comment"></CommentList>
                        </DisclosurePanel>
                    </Disclosure>
                </div>
                <EditDeleteDropdown class="dark:text-white self-start" :post="post" :comment="comment" @edit="startCommentEdit(comment)"
                                    @delete="deleteComment(comment)"/>
            </div>
        </div>
    </div>
</template>
