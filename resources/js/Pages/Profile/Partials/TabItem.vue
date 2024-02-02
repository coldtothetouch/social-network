<script setup>
defineProps({
    text: String,
    selected: Boolean,
})
</script>

<template>
    <button
        :class="[
          'px-3 py-2 outline-none',
          selected
            ? 'bg-white text-blue-500 border-b-2 border-blue-500'
            : 'text-gray-500',
        ]"
    >
        {{ text }}
    </button>
</template>

<style scoped>

</style>
