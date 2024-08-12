<script setup>

import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import GroupListItems from "@/Components/App/GroupListItems.vue";
import GroupModal from "@/Components/App/GroupModal.vue";
import {ref} from "vue";

const showCreateGroup = ref(false)

</script>

<template>
    <div class="p-5 bg-white rounded-md shadow overflow-hidden flex flex-col">
        <div class="lg:hidden flex-1 block">
            <Disclosure v-slot="{ open }">
                <div class="flex gap-3">
                    <DisclosureButton class="flex items-center w-full justify-between">
                        <h2 class="text-2xl font-bold text-left">My Groups</h2>


                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-6 h-6" :class="open ? 'rotate-180 transform' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>

                    </DisclosureButton>
                    <div @click="showCreateGroup = true"
                         class="bg-indigo-500 cursor-pointer py-1 px-3 text-white rounded-lg">Create
                    </div>
                </div>

                <DisclosurePanel class="h-full flex flex-col">
                    <GroupListItems/>
                </DisclosurePanel>
            </Disclosure>
        </div>

        <div class="h-full overflow-hidden flex-col hidden lg:flex">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">My Groups</h2>
                <div @click="showCreateGroup = true"
                     class="bg-indigo-500 cursor-pointer py-1 px-3 text-white rounded-lg">Create
                </div>
            </div>
            <GroupListItems/>
        </div>

    </div>

    <GroupModal v-model="showCreateGroup"/>
</template>
