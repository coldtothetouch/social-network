<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    errorTitle: String
})
</script>

<template>
    <AuthenticatedLayout>
        <h1 class="text-center text-3xl font-bold pt-16">{{ errorTitle }}</h1>
    </AuthenticatedLayout>
</template>
