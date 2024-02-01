<script setup>
defineProps({
    image: String,
    name: String,
})
</script>

<template>
    <div class="flex gap-3 items-center py-3 px-5 hover:bg-gray-200 rounded-md cursor-pointer border border-1">
        <img :src="image" class="w-[65px] rounded-full">
        <div>
            <h3 class="text-md font-semibold">{{ name }}</h3>
        </div>
    </div>
</template>

<style scoped>

</style>
