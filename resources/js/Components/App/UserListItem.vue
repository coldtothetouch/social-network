<script setup>
import {Link} from '@inertiajs/vue3'
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    user: {
        type: Object,
        required: true,
    },
    forApprove: {
        type: Boolean,
        default: false,
    },
    showUserRoleDropdown: {
        type: Boolean,
        default: false,
    },
    disableUserRoleDropdown: {
        type: Boolean,
        default: false,
    }
})
const emit = defineEmits(['approve', 'reject', 'roleChange', 'userKick'])
</script>

<template>
    <div class="flex gap-3 items-center py-3 px-5 hover:bg-gray-200 rounded-md">
        <Link :href="route('profile.index', user)">
            <img :src="user.avatar_path" class="size-[45px] rounded-full" alt="avatar">
        </Link>
        <div class="flex justify-between flex-1 items-center">
            <Link :href="route('profile.index', user)">
                <h3 class="text-md font-semibold hover:text-underline">{{ user.name }}</h3>
            </Link>
            <div v-if="forApprove" class="flex gap-2">
                <PrimaryButton @click.prevent="$emit('approve')">Approve</PrimaryButton>
                <PrimaryButton @click.prevent="$emit('reject')">Reject</PrimaryButton>
            </div>
            <div v-if="showUserRoleDropdown">
                <select :disabled="disableUserRoleDropdown" @change="$emit('roleChange', user, $event.target.value)"
                    class="rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm/6">
                    <option :selected="user.role === 'admin'">admin</option>
                    <option :selected="user.role === 'subscriber'">subscriber</option>
                </select>
                <button @click="$emit('userKick', user)" :disabled="disableUserRoleDropdown" class="text-white cursor-pointer ml-3 text-xs py-1.5 px-2 rounded bg-red-500 hover:bg-red-600 disabled:bg-red-300">
                    Kick
                </button>
            </div>
        </div>
    </div>
</template>
