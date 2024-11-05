<script setup>
import PostItem from "@/Components/App/PostItem.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ref} from "vue";
import PostModal from "@/Components/App/PostModal.vue";
import AttachmentPreviewModal from "@/Components/App/AttachmentPreviewModal.vue";
import {usePage} from "@inertiajs/vue3";

defineProps({
    post: Object,
})

const showEditModal = ref(false)
const showPreviewModal = ref(false)

const editPost = ref({})
const previewPost = ref({})

function openEditModal(post) {
    editPost.value = post
    showEditModal.value = true
}

function openPreviewModal(post, index) {
    previewPost.value = {post, index}
    showPreviewModal.value = true
}

function onModalHide() {
    editPost.value = {
        id: null,
        body: '',
        user: usePage().props.auth.user
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8">
            <PostItem :post="post"
                      @editClick="openEditModal"
                      @attachmentClick="openPreviewModal"
            />
        </div>

        <PostModal
            :post="editPost"
            v-model="showEditModal"
            @hide="onModalHide"
        />

        <AttachmentPreviewModal
            :attachments="previewPost.post?.attachments || []"
            v-model:index="previewPost.index"
            v-model="showPreviewModal"
            @hide="onModalHide"
        />
    </AuthenticatedLayout>
</template>
