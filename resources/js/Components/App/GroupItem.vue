<script setup>
import {Link} from '@inertiajs/vue3'

defineProps({
    group: Object,
})

</script>

<template>
    <Link :href="route('groups.show', group.slug)" class="flex gap-3 items-center py-3 px-5 hover:bg-gray-100 rounded-md cursor-pointer border border-1">
        <img :src="group.avatar_path" class="w-[65px] rounded-full" alt="avatar">
        <div class="flex gap-3 justify-between items-center w-full">
            <div>
                <h3 class="text-md font-semibold">{{ group.name }}</h3>
                <div class="text-sm text-gray-500" v-html="group.short_description"/>
            </div>
            <div class="text-sm">{{ group.status === 'approved' ? (group.role === 'admin' ? 'admin' : '' ) : 'not approved' }}</div>
        </div>
    </Link>
</template>

<style scoped>

</style>
