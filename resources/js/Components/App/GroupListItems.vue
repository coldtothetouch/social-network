<script setup>

import TextInput from "@/Components/TextInput.vue";
import GroupItem from "@/Components/App/GroupItem.vue";
import {ref} from "vue";

const search = ref('');

const props = defineProps({
    groups: Array,
})

</script>

<template>
    <TextInput :model-value="search" class="dark:bg-slate-700 w-full my-5" placeholder="Search for groups" />
    <div class="h-[200px] lg:flex-1 overflow-y-auto">
        <div v-if="false" class="text-center text-gray-400">
            You are not part of any group
        </div>
        <div v-else class="flex gap-3 flex-col h-full">
            <GroupItem v-for="group in groups" :group="group"/>
        </div>
    </div>
</template>
