<script setup>

import TextInput from "@/Components/TextInput.vue";
import {ref} from "vue";

const search = ref('');

</script>

<template>
    <TextInput :model-value="search" placeholder="Search for friends" class="w-full my-5"/>
    <div class="h-[200px] lg:flex-1 overflow-y-auto">
        <div v-if="false" class="text-center text-gray-400">
            You don't have friends yet
        </div>
        <div v-else class="flex flex-col gap-3">
        </div>
    </div>
</template>
