<script setup>
import {ref} from "vue";
import TextareaInput from "@/Components/App/TextareaInput.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import PostModal from "@/Components/App/PostModal.vue";

const user = usePage().props.auth.user
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
    <div class="bg-white rounded-md shadow p-5 mb-5">
        <div @click="showCreatePostModal" class="w-full overflow-hidden border border-2 py-2 px-3 rounded-lg text-gray-500">Click here to create a new post</div>
<!--        <div class="flex gap-3 justify-between mt-3">
            <button type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold
        text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-indigo-600 relative">
                Attach Files
                <input type="file" class="absolute left-0 right-0 top-0 bottom-0 opacity-0">
            </button>

            <button @click="submit" type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold
        text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Submit
            </button>
        </div>-->
        <PostModal :post="post" v-model="showModal"/>
    </div>
</template>

<style scoped>

</style>
