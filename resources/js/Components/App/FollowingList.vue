<script setup>

import TextInput from "@/Components/TextInput.vue";
import {ref} from "vue";
import FollowingItem from "@/Components/App/FollowingItem.vue";
import GroupItem from "@/Components/App/GroupItem.vue";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import FollowingListItems from "@/Components/App/FollowingListItems.vue";

const search = ref('');
</script>

<template>
    <div class="p-5 bg-white rounded-md shadow overflow-hidden flex flex-col">


        <div class="lg:hidden flex-1 block">
            <Disclosure v-slot="{ open }">
                <DisclosureButton class="flex items-center w-full justify-between">
                    <h2 class="text-2xl font-bold">My Followings</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6" :class="open ? 'rotate-180 transform' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </DisclosureButton>

                <DisclosurePanel class="h-full flex flex-col">
                    <FollowingListItems/>
                </DisclosurePanel>
            </Disclosure>
        </div>

        <div class="hidden lg:flex flex-col flex-1 h-full">

            <h2 class="text-2xl font-bold">My Followings</h2>
            <FollowingListItems/>
        </div>
    </div>
</template>

<style scoped>

</style>
