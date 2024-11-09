<script setup>

import {EllipsisVerticalIcon, PencilIcon, EyeIcon, ClipboardIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {usePage, Link} from "@inertiajs/vue3";
import {computed} from "vue";

defineEmits(['edit', 'delete', 'pin']);

const props = defineProps({
    post: {
        type: Object,
        default: null,
    },
    comment: {
        type: Object,
        default: null,
    },
})

const user = computed(() => props.comment?.user || props.post?.user)
const authUser = usePage().props.auth.user

const editAllowed = computed(() => user.value.id === authUser.id)

const deleteAllowed = computed(() => {
    if (user.value.id === authUser.id) return true
    return props.post.group?.role === 'admin';
})

const pinAllowed = computed(() => {
    return props.post.user.id === authUser.id && props.post.group === null || props.post.group?.role === 'admin'
})

function copyToClipboard() {
    navigator.clipboard.writeText(route('posts.show', props.post));
}

</script>

<template>
    <Menu as="div"
          class="relative inline-block text-left">
        <div class="rounded-full">
            <MenuButton
                class="hover:bg-gray-100 p-2 rounded-full z-10"
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
                class="absolute z-20 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
            >
                <div class="px-1 py-1">
                    <MenuItem v-if="pinAllowed" v-slot="{ active }">
                        <button @click="$emit('pin')"
                                :class="[
                              active ? 'bg-gray-100' : '',
                              'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="mr-2 h-5 w-5 text-gray-400" viewBox="0 0 16 16">
                                <path
                                    d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a6 6 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707s.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a6 6 0 0 1 1.013.16l3.134-3.133a3 3 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146m.122 2.112v-.002zm0-.002v.002a.5.5 0 0 1-.122.51L6.293 6.878a.5.5 0 0 1-.511.12H5.78l-.014-.004a5 5 0 0 0-.288-.076 5 5 0 0 0-.765-.116c-.422-.028-.836.008-1.175.15l5.51 5.509c.141-.34.177-.753.149-1.175a5 5 0 0 0-.192-1.054l-.004-.013v-.001a.5.5 0 0 1 .12-.512l3.536-3.535a.5.5 0 0 1 .532-.115l.096.022c.087.017.208.034.344.034q.172.002.343-.04L9.927 2.028q-.042.172-.04.343a1.8 1.8 0 0 0 .062.46z"/>
                            </svg>
                            {{ props.post.is_pinned ? 'Unpin Post' : 'Pin Post'}}
                        </button>
                    </MenuItem>

                    <MenuItem v-slot="{ active }">
                        <Link :href="route('posts.show', post)"
                              :class="[
                              active ? 'bg-gray-100' : '',
                              'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <EyeIcon
                                class="mr-2 h-5 w-5 text-gray-400"
                                aria-hidden="true"
                            />
                            Open Post
                        </Link>
                    </MenuItem>

                    <MenuItem v-if="editAllowed" v-slot="{ active }">
                        <button @click="copyToClipboard"
                                :class="[
                              active ? 'bg-gray-100' : '',
                              'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <ClipboardIcon
                                class="mr-2 h-5 w-5 text-gray-400"
                                aria-hidden="true"
                            />
                            Copy URL
                        </button>
                    </MenuItem>

                    <MenuItem v-if="editAllowed" v-slot="{ active }">
                        <button @click="$emit('edit')"
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

                    <MenuItem v-if="deleteAllowed" v-slot="{ active }">
                        <button
                            @click="$emit('delete')"
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
</template>

<style scoped>

</style>
