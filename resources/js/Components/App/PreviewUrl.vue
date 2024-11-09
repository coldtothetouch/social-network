<script setup>

defineProps({
    preview: {
        type: Object,
        default: {},
    },
    url: String,
})

</script>

<template>
    <a :href="url" target="_blank" v-if="preview && preview.title">
        <img :src="preview.image" :alt="preview.title" class="max-w-full">
        <div class="border p-2 bg-indigo-50">
            <h3 class="text-lg font-semibold">{{ preview.title }}</h3>
            <p>{{ preview.description }}</p>
        </div>
    </a>
    <a v-else-if="url" :href="url" target="_blank"  class="text-blue-500 hover:underline hover:text-blue-600">
        {{ url }}
    </a>
</template>
