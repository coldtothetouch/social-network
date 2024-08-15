<script setup>
defineProps({
    group: Object,
})
</script>

<template>
    <div class="flex gap-3 items-center py-3 px-5 hover:bg-gray-100 rounded-md cursor-pointer border border-1">
        <img :src="group.avatar_path" class="w-[65px] rounded-full" alt="avatar">
        <div class="flex gap-3 justify-between items-center w-full">
            <div>
                <h3 class="text-md font-semibold">{{ group.name }}</h3>
                <div class="text-sm text-gray-500">{{ group.short_description }}</div>
            </div>
            <div class="text-sm">{{ group.status === 'approved' ? (group.role === 'admin' ? 'admin' : '' ) : 'not approved' }}</div>
        </div>
    </div>
</template>

<style scoped>

</style>
