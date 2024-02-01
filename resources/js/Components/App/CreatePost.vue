<script setup>
import {ref} from "vue";

const postCreating = ref(false);
</script>

<template>
    <div class="bg-white rounded-md shadow p-5 mb-5">
        <div @click="postCreating = true" class="py-3 px-3 text-gray-500 border border-gray-300 rounded-lg">
            Click here to create a new post
        </div>
        <div v-if="postCreating" class="flex gap-3 justify-between mt-3">
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold
        text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-indigo-600 relative">
                Attach Files
                <input type="file" class="absolute left-0 right-0 top-0 bottom-0 opacity-0">
            </button>

            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold
        text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Submit
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
