<script setup>
import {usePage} from "@inertiajs/vue3";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed} from "vue";

const authUser = usePage().props.auth.user

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object,
    }
});

const isMyProfile = computed(() => authUser && authUser.id === props.user.id)

</script>

<template>
    <AuthenticatedLayout>
        <div class="w-[1300px] mx-auto h-full overflow-y-auto">
            <div >
                <img src="https://www.prodraw.net/fb_cover/images/fb_cover_48.jpg"
                     class="w-full object-cover h-[300px]">
                <div class="bg-white border-b flex">
                    <img src="https://picsum.photos/100"
                         class="w-[128px] h-[128px] rounded-full -mt-[65px] ml-[50px] flex">
                    <div class="flex items-center justify-between flex-1 p-3">
                        <h2 class="font-bold text-lg">{{ user.name }}</h2>

                        <PrimaryButton v-if="isMyProfile">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                            </svg>
                            Edit profile
                        </PrimaryButton>
                    </div>
                </div>
            </div>
            <div class="">
                <TabGroup>
                    <TabList class="flex space-x-1 bg-blue-900/20 bg-white ">
                        <Tab v-slot="{ selected }" as="template" v-if="isMyProfile">
                            <TabItem text="About" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Posts" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Followers" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Followings" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Photos" :selected="selected"/>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel
                            v-if="isMyProfile"
                            class='shadow'>
                            <Edit :mustVerifyEmail="mustVerifyEmail" :status="status"/>
                        </TabPanel>
                        <TabPanel
                            class='shadow bg-white p-5'>
                            Posts
                        </TabPanel>
                        <TabPanel
                            class='shadow bg-white p-5'>
                            Followers
                        </TabPanel>
                        <TabPanel
                            class='shadow bg-white p-5'>
                            Followings
                        </TabPanel>
                        <TabPanel
                            class='shadow bg-white p-5'>
                            Photos
                        </TabPanel>

                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
