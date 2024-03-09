<script setup>
import {
    Menu, MenuButton, MenuItems, MenuItem,
    Disclosure, DisclosureButton, DisclosurePanel
} from '@headlessui/vue'
import {PencilIcon, TrashIcon, EllipsisVerticalIcon, ArrowUpTrayIcon} from '@heroicons/vue/20/solid'
import {DocumentIcon} from '@heroicons/vue/24/outline'
import {isImage} from '@/helpers.js'
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    post: Object,
})

const emit = defineEmits(['editClick'])

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
            <div v-if="post.body.length < 255">
                <div class="ck-content-output" v-html="post.body"/>
            </div>
            <Disclosure v-slot="{ open }" v-else>

                <div class="ck-content-output" v-show="!open" v-html="post.body.substring(0, 255)+'...'"/>

                <DisclosurePanel>
                    <div class="ck-content-output" v-html="post.body"/>
                </DisclosurePanel>

                <div class="flex justify-end">
                    <DisclosureButton>
                        <p class="cursor-pointer text-blue-600 hover:underline my-2">{{ open ? 'Hide' : 'Read More' }}</p>
                    </DisclosureButton>
                </div>
            </Disclosure>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 sm:grid-cols-1 gap-3">
            <template v-for="(attachment, i) of post.attachments.slice(0, 3)">
                <div class="relative group relative">

                    <div v-if="i === 3" class="absolute top-0 left-0 bottom-0 right-0 flex items-center justify-center text-2xl text-white bg-black/40">
                        +{{post.attachments.length - 3}} more...
                    </div>

                    <button
                        class="absolute opacity-0 group-hover:opacity-100 transition-all right-5 top-5 h-10 w-10 bg-gray-400 rounded-lg flex justify-center items-center hover:bg-gray-500">
                        <ArrowUpTrayIcon class="text-white w-6 h-6"/>
                    </button>

                    <img v-if="isImage(attachment)" :src="attachment.url" class="object-cover aspect-square rounded-md" :alt="attachment.name">

                    <div v-else class="bg-gray-100 h-full flex flex-col items-center justify-center  text-gray-400">
                        <DocumentIcon class="w-40 h-40"/>

                        {{ attachment.name }}
                    </div>
                </div>
            </template>
        </div>

        <div class="flex gap-5 justify-between mt-5">
            <button class="flex flex-1 justify-center gap-3 border py-5 px-10 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                     class="w-6 h-6 text-gray-400">
                    <path
                        d="M7.493 18.5c-.425 0-.82-.236-.975-.632A7.48 7.48 0 0 1 6 15.125c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75A.75.75 0 0 1 15 2a2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23h-.777ZM2.331 10.727a11.969 11.969 0 0 0-.831 4.398 12 12 0 0 0 .52 3.507C2.28 19.482 3.105 20 3.994 20H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 0 1-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227Z"/>
                </svg>
                Like
            </button>
            <button class="flex flex-1 justify-center gap-3 border py-5 px-10 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                     class="w-6 h-6 text-gray-400">
                    <path
                        d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 0 0-1.032-.211 50.89 50.89 0 0 0-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 0 0 2.433 3.984L7.28 21.53A.75.75 0 0 1 6 21v-4.03a48.527 48.527 0 0 1-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979Z"/>
                    <path
                        d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 0 0 1.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0 0 15.75 7.5Z"/>
                </svg>
                Comment
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
