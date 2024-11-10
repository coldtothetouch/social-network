<script setup>

import TextInput from "@/Components/TextInput.vue";
import {ref} from "vue";
import UserListItem from "@/Components/App/UserListItem.vue";

defineProps({
    followings: Array,
})

const search = ref('');

</script>

<template>
    <TextInput :model-value="search" placeholder="Search for friends" class="dark:bg-slate-700 w-full my-5"/>
    <div class="h-[200px] lg:flex-1 overflow-y-auto">
        <div v-if="followings.length">
            <UserListItem v-for="user in followings" :user="user"/>
        </div>
        <div v-else class="flex flex-col gap-3 text-center text-gray-400">
            You have no followings
        </div>
    </div>
</template>
