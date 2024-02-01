<script setup>
defineProps({
    image: String,
    title: String,
    description: String,
})
</script>

<template>
    <div class="flex gap-3 items-center py-3 px-5 hover:bg-gray-100 rounded-md cursor-pointer border border-1">
        <img :src="image" class="w-[65px] rounded-full">
        <div>
            <h3 class="text-md font-semibold">{{ title }}</h3>
            <div class="text-sm text-gray-500">{{ description }}</div>
        </div>
    </div>
</template>

<style scoped>

</style>
