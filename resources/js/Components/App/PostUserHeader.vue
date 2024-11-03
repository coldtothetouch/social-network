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
                 alt="avatar" class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square">
        </Link>
        <div class="flex flex-col">

            <h4 class="text-lg font-semibold">
                <Link :href="route('profile.index', post.user)" class="hover:underline">{{ post.user.name }}</Link>
                <Link :href="route('groups.show', post.group)" v-if="post.group"> > <span
                    class="hover:underline">{{ post.group.name }}</span></Link>
            </h4>

            <small v-show="showTime" class="text-sm text-gray-400">{{ post.updated_at }}</small>
        </div>
    </div>
</template>

<style scoped>

</style>
