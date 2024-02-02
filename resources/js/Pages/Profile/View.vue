<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed, ref} from "vue";
import { XMarkIcon, CheckIcon } from "@heroicons/vue/16/solid"

const authUser = usePage().props.auth.user
const coverImageSrc = ref('')
const showFlash = ref(true)

const props = defineProps({
    errors: Object,
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

const ImagesForm = useForm({
    cover: null,
    avatar: null,
});

const isMyProfile = computed(() => authUser && authUser.id === props.user.id)

function onCoverChange(event)
{
    ImagesForm.cover = event.target.files[0]

    if (ImagesForm.cover) {
        const reader = new FileReader()
        reader.onload = () => {
            coverImageSrc.value = reader.result
        }
        reader.readAsDataURL(ImagesForm.cover)
    }
}

function cancelCoverImage()
{
    ImagesForm.cover = null
    coverImageSrc.value = null
}

function updateCoverImage()
{
    ImagesForm.post(route('updateImage'), {
        onSuccess: () => {
            setTimeout(() => {
                showFlash.value = false
            }, 3000)
            cancelCoverImage()
        }
    })
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-[1300px] mx-auto h-full overflow-y-auto">
            <div
                v-show="showFlash && status === 'cover-image-updated'"
                class="mt-2 font-medium bg-emerald-500 text-white p-4 text-center"
            >
                Cover image has been updated.
            </div>
            <div
                v-if="errors.cover"
                class="mt-2 font-medium bg-red-500 text-white p-4 text-center"
            >
                {{ errors.cover }}
            </div>
            <div class="relative group">

                <img :src="coverImageSrc || user.cover_url || '/img/default_cover.jpg' "
                     class="w-full object-cover h-[300px]">
                <div class="absolute top-3 right-3">
                    <button v-if="!coverImageSrc" class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 opacity-0 group-hover:opacity-100 text-xs transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <input type="file" class="absolute left-0 top-0 right-0 bottom-0 opacity-0" @change="onCoverChange">
                        Update Cover Image
                    </button>
                    <div v-else class="flex gap-2">
                        <button @click="cancelCoverImage" class="inline-flex bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 opacity-0 group-hover:opacity-100 text-xs transition-all flex items-center gap-2">
                            <XMarkIcon class="h-4 w-4"/>
                            Cancel
                        </button>
                        <button @click="updateCoverImage" class="inline-flex bg-gray-800 hover:bg-gray-700 text-gray-300 py-2 px-3 opacity-0 group-hover:opacity-100 text-xs transition-all flex items-center gap-2">
                            <CheckIcon class="h-4 w-4"/>
                            Submit
                        </button>
                    </div>
                </div>

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
