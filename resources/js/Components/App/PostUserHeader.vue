<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    post: Object,
    showTime: {
        type: Boolean,
        default: true,
    }
})

</script>

<template>
    <div class="flex gap-3 items-center">
        <Link :href="route('profile.index', post.user)">
            <img :src="post.user.avatar_path || '/img/default_avatar.webp' "
                 alt="avatar"
                 class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square">
        </Link>
        <div class="flex flex-col">

            <h4 class="text-lg font-semibold">
                <Link :href="route('profile.index', post.user)" class="hover:underline">{{ post.user.name }}</Link>
                <Link :href="route('groups.show', post.group)" v-if="post.group"> > <span
                    class="hover:underline">{{ post.group.name }}</span></Link>
            </h4>

            <small v-show="showTime" class="text-sm text-gray-400">{{ post.updated_at }}</small>
        </div>
        <svg v-if="post.is_pinned" class="size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
             viewBox="0 0 16 16">
            <path
                d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a6 6 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707s.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a6 6 0 0 1 1.013.16l3.134-3.133a3 3 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146"/>
        </svg>
    </div>
</template>
