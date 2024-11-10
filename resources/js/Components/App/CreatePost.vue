<script setup>
import {ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import PostModal from "@/Components/App/PostModal.vue";

const user = usePage().props.auth.user

defineProps({
    group: {
        type: Object,
        default: null,
    }
})

const post = useForm({
    id: null,
    body: '',
    user: user
})

const showModal = ref(false)

function showCreatePostModal() {
    showModal.value = true;
}

</script>

<template>
    <div class="dark:bg-slate-800 bg-white rounded-md shadow p-5 mb-5">
        <div @click="showCreatePostModal" class="dark:bg-slate-700 dark:border-slate-600 w-full overflow-hidden border border-2 py-2 px-3 rounded-lg text-gray-500">
            Click here to create a new post
        </div>
        <PostModal :post="post" :group="group" v-model="showModal"/>
    </div>
</template>
