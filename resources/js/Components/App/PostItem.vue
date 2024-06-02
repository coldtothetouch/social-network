<script setup>
import {
    Menu, MenuButton, MenuItems, MenuItem,
    Disclosure, DisclosureButton, DisclosurePanel
} from '@headlessui/vue'
import {
    PencilIcon,
    TrashIcon,
    EllipsisVerticalIcon,
    ArrowUpTrayIcon,
} from '@heroicons/vue/24/outline'
import {HandThumbUpIcon, ChatBubbleLeftRightIcon} from '@heroicons/vue/24/solid'
import {DocumentIcon} from '@heroicons/vue/24/outline'
import {isImage} from '@/helpers.js'
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import {router, usePage} from "@inertiajs/vue3";
import axios from "axios";
import TextareaInput from "@/Components/App/TextareaInput.vue";
import IndigoButton from "@/Components/App/IndigoButton.vue";
import {ref} from "vue";
import ReadMoreOrHide from "@/Components/App/ReadMoreOrHide.vue";

const user = usePage().props.auth.user

const props = defineProps({
    post: Object,
})

const emit = defineEmits(['editClick', 'attachmentClick'])

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

</script>

<template>
    <div class="border p-5 rounded-lg bg-white shadow">
        <div class="flex justify-between  items-center mb-3">
            <PostUserHeader :post="post"/>
            <Menu as="div" class="relative inline-block text-left z-10">
                <div class="rounded-full">
                    <MenuButton
                        class="hover:bg-gray-100 p-2 rounded-full"
                    >
                        <EllipsisVerticalIcon class="w-4 h-4 text-gray-400"/>
                    </MenuButton>
                </div>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                        class="absolute right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                    >
                        <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                                <button @click="openEditModal"
                                        :class="[
                  active ? 'bg-gray-100' : '',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                                >
                                    <PencilIcon
                                        class="mr-2 h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                    Edit
                                </button>
                            </MenuItem>

                            <MenuItem v-slot="{ active }">
                                <button
                                    @click="deletePost"
                                    :class="[
                                active ? 'bg-gray-100' : '',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                                >
                                    <TrashIcon
                                        class="mr-2 h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                    Delete
                                </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
        <div class="overflow-hidden" v-if="post.body">
            <ReadMoreOrHide :content="post.body"/>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-2 sm:grid-cols-1 gap-3">
            <template v-for="(attachment, i) of post.attachments.slice(0, 4)">
                <div @click="openAttachment(i)" class="relative group relative cursor-pointer">

                    <div v-if="i === 3 && post.attachments.length > 4"
                         class="absolute top-0 left-0 bottom-0 right-0 flex items-center justify-center text-2xl text-white bg-black/40 rounded-md">
                        +{{ post.attachments.length - 3 }} more...
                    </div>

                    <a @click.stop :href="route('post.attachment.download', attachment)"
                       class="absolute opacity-0 group-hover:opacity-100 transition-all right-5 top-5 h-10 w-10 bg-gray-400 rounded-lg flex justify-center items-center hover:bg-gray-500">
                        <ArrowUpTrayIcon class="text-white w-6 h-6"/>
                    </a>

                    <img v-if="isImage(attachment)" :src="attachment.url" class="object-cover aspect-square rounded-md"
                         :alt="attachment.name">

                    <div v-else
                         class="bg-gray-100 aspect-square flex flex-col items-center justify-center rounded-md text-gray-400">
                        <DocumentIcon class="w-40 h-40"/>
                        {{ attachment.name }}
                    </div>
                </div>
            </template>
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
                             class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square" alt="avatar">
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
                                     class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square" alt="avatar">
                            </a>

                            <div class="w-full flex flex-col">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-lg font-semibold">
                                        <a href="javascript:void(0)" class="hover:underline">{{ comment.user.name }}</a>
                                    </h4>

                                    <small class="text-xs text-gray-400">{{ comment.created_at }}</small>
                                </div>

                                <ReadMoreOrHide :content="comment.body"/>
                            </div>
                        </div>
                    </div>
                </div>

            </DisclosurePanel>
        </Disclosure>

    </div>
</template>

<style scoped>

</style>
