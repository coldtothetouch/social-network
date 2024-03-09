<script setup>
import PostItem from "@/Components/App/PostItem.vue";
import PostModal from "@/Components/App/PostModal.vue";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

defineProps({
    posts: Array
})

const authUser = usePage().props.auth.user
const showEditModal = ref(false)
const editPost = ref({})

function openEditModal(post) {
    editPost.value = post
    showEditModal.value = true
}

function onModalHide() {
    editPost.value = {
        id: null,
        body: '',
        user: authUser
    }
}

</script>

<template>
    <div class="flex flex-col gap-3 h-full overflow-y-auto">
        <PostItem v-for="post of posts" :key="post.id" :post="post" @editClick="openEditModal"/>
    </div>
    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide"/>
</template>
