<script setup>

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
        <a href="javascript:void(0)">
            <img :src="post.user.avatar_url"
                 class="w-[50px] rounded-full border border-2 transition-all hover:border-blue-400 object-cover aspect-square">
        </a>
        <div class="flex flex-col">

            <h4 class="text-lg font-semibold">
                <a href="javascript:void(0)" class="hover:underline">{{ post.user.name }}</a>
                <a v-if="post.group" href="javascript:void(0)"> > <span
                    class="hover:underline">{{ post.group.name }}</span></a>
            </h4>

            <small v-show="showTime" class="text-sm text-gray-400">{{ post.updated_at }}</small>
        </div>
    </div>
</template>

<style scoped>

</style>
