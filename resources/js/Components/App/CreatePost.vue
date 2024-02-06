<script setup>
import {ref} from "vue";
import TextareaInput from "@/Components/App/TextareaInput.vue";
import {useForm} from "@inertiajs/vue3";

const postCreating = ref(false);

const form = useForm({
    body: ''
})

function submit() {
    form.post(route('post.create'), {
        onSuccess: () => {
            form.reset()
        }
    })
}

</script>

<template>
    <div class="bg-white rounded-md shadow p-5 mb-5">
        <TextareaInput @click="postCreating = true" v-model="form.body" class="w-full mb-3" rows="1" placeholder="Click here to create a new post">
        </TextareaInput>
        <div v-if="postCreating" class="flex gap-3 justify-between mt-3">
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
        </div>
    </div>
</template>

<style scoped>

</style>
