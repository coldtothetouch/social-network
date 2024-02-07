<script setup>

import PostItem from "@/Components/App/PostItem.vue";
import PostModal from "@/Components/App/PostModal.vue";
import {ref} from "vue";

defineProps({
    posts: Array
})

const showEditModal = ref(false)
const editPost = ref({})

function openEditModal(post) {
    editPost.value = post
    showEditModal.value = true
}

</script>

<template>
    <div class="flex flex-col gap-3 h-full overflow-y-auto">
        <PostItem v-for="post of posts" :key="post.id" :post="post" @editClick="openEditModal"/>
    </div>
    <PostModal :post="editPost" v-model="showEditModal"/>
</template>

<style scoped>

</style>
