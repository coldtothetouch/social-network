<script setup>
import {Link} from '@inertiajs/vue3'

defineProps({
    group: Object,
})

</script>

<template>
    <Link :href="route('groups.show', group.slug)"
          class="flex gap-3 items-center py-3 px-5 hover:bg-gray-100 rounded-md cursor-pointer border border-1">
        <img :src="group.avatar_path" class="w-[45px] rounded-full" alt="avatar">
        <h3 class="text-md font-semibold">{{ group.name }}</h3>
    </Link>
</template>

<style scoped>

</style>
