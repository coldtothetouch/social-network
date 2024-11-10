<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'button'
    }
})
</script>

<template>
    <button
        :type="props.type"
        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-500 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
    >
        <slot/>
    </button>
</template>

<style scoped>

</style>
