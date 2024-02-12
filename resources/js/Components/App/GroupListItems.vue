<script setup>

import TextInput from "@/Components/TextInput.vue";
import GroupItem from "@/Components/App/GroupItem.vue";
import {ref} from "vue";

const search = ref('');

</script>

<template>
    <TextInput :model-value="search" placeholder="Search for groups" class="w-full my-5"/>
    <div class="h-[200px] lg:flex-1 overflow-y-auto">
        <div v-if="false" class="text-center text-gray-400">
            You are not joined to any groups
        </div>
        <div v-else class="flex gap-3 flex-col h-full">
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />

            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developers"
                       description="Lorem ipsum dolor sit amet"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
