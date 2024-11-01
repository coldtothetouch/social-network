<script setup>
import { Link } from '@inertiajs/vue3'
import PrimaryButton from "@/Components/PrimaryButton.vue";
defineProps({
    user: {
        type: Object,
        required: true,
    },
    forApprove: {
        type: Boolean,
        default: false,
    }
})

const emit = defineEmits(['approve', 'reject'])
</script>

<template>
    <Link :href="route('profile.index', user)" class="flex gap-3 items-center py-3 px-5 hover:bg-gray-200 rounded-md cursor-pointer">
        <img :src="user.avatar_path" class="size-[45px] rounded-full" alt="avatar">
        <div class="flex justify-between flex-1 items-center">
            <h3 class="text-md font-semibold">{{ user.name }}</h3>
           <div class="flex gap-2">
               <PrimaryButton v-if="forApprove" @click.prevent="$emit('approve')">Approve</PrimaryButton>
               <PrimaryButton v-if="forApprove" @click.prevent="$emit('reject')">Reject</PrimaryButton>
           </div>
        </div>
    </Link>
</template>
