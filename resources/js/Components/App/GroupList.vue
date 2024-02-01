<script setup>

import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import GroupListItems from "@/Components/App/GroupListItems.vue";

</script>

<template>
    <div class="p-5 bg-white rounded-md shadow overflow-hidden flex flex-col">
        <div class="lg:hidden flex-1 block">
            <Disclosure v-slot="{ open }">
                <DisclosureButton class="flex items-center w-full justify-between">
                    <h2 class="text-2xl font-bold text-left">My Groups</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6" :class="open ? 'rotate-180 transform' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </DisclosureButton>

                <DisclosurePanel class="h-full flex flex-col">
                    <GroupListItems/>
                </DisclosurePanel>
            </Disclosure>
        </div>

        <div class="h-full overflow-hidden flex-col hidden lg:flex">
            <h2 class="text-2xl font-bold">My Groups</h2>
            <GroupListItems class="h-full"/>
        </div>

    </div>
</template>

<style scoped>

</style>
