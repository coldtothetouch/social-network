<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed, ref} from "vue";
import {CheckIcon} from "@heroicons/vue/24/outline"
import {XMarkIcon, CameraIcon} from "@heroicons/vue/24/solid"
import DangerButton from "@/Components/DangerButton.vue";

const authUser = usePage().props.auth.user
const coverImageSrc = ref('')
const avatarImageSrc = ref('')
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

function onCoverChange(event) {
    ImagesForm.cover = event.target.files[0]

    if (ImagesForm.cover) {
        const reader = new FileReader()
        reader.onload = () => {
            coverImageSrc.value = reader.result
        }
        reader.readAsDataURL(ImagesForm.cover)
    }
}

function resetCoverImage() {
    ImagesForm.cover = null
    coverImageSrc.value = null
}

function updateCoverImage() {
    ImagesForm.post(route('profile.updateImage'), {
        onSuccess: () => {
            setTimeout(() => {
                showFlash.value = false
            }, 3000)
            resetCoverImage()
            showFlash.value = true
        }
    })
}

function onAvatarChange(event) {
    ImagesForm.avatar = event.target.files[0]

    if (ImagesForm.avatar) {
        const reader = new FileReader()
        reader.onload = () => {
            avatarImageSrc.value = reader.result
        }
        reader.readAsDataURL(ImagesForm.avatar)
    }
}

function updateAvatarImage() {
    ImagesForm.post(route('profile.updateImage'), {
        onSuccess: () => {
            setTimeout(() => {
                showFlash.value = false
            }, 3000)
            resetAvatarImage()
            showFlash.value = true
        }
    })
}

function resetAvatarImage() {
    avatarImageSrc.value = null
    ImagesForm.avatar = null
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-[1300px] mx-auto h-full overflow-y-auto">
            <div
                v-show="showFlash && status"
                class="mt-2 font-medium bg-emerald-500 text-white p-4 text-center"
            >
                {{ status }}
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
                    <button v-if="!coverImageSrc"
                            class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 opacity-0 group-hover:opacity-100 text-xs transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                        </svg>
                        <input type="file" class="absolute left-0 top-0 right-0 bottom-0 opacity-0"
                               @change="onCoverChange">
                        Update Cover Image
                    </button>
                    <div v-else class="flex gap-2">
                        <button @click="resetCoverImage"
                                class="inline-flex bg-gray-50 hover:bg-gray-100 text-gray-800 py-2 px-3 opacity-0 group-hover:opacity-100 text-xs transition-all flex items-center gap-2">
                            <XMarkIcon class="h-4 w-4"/>
                            Cancel
                        </button>
                        <button @click="updateCoverImage"
                                class="inline-flex bg-gray-800 hover:bg-gray-700 text-gray-300 py-2 px-3 opacity-0 group-hover:opacity-100 text-xs transition-all flex items-center gap-2">
                            <CheckIcon class="h-4 w-4"/>
                            Submit
                        </button>
                    </div>
                </div>

                <div class="flex bg-white">
                    <div
                        class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
                        <img :src="avatarImageSrc  || user.avatar_url || '/img/default_avatar.webp'"
                             class="w-full h-full object-cover rounded-full">
                        <button
                            v-if="!avatarImageSrc"
                            class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100">
                            <CameraIcon class="w-8 h-8"/>

                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                   @change="onAvatarChange"/>
                        </button>
                        <div v-else class="absolute top-1 right-0 flex flex-col gap-2">
                            <button
                                @click="resetAvatarImage"
                                class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                <XMarkIcon class="h-5 w-5"/>
                            </button>
                            <button
                                @click="updateAvatarImage"
                                class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                                <CheckIcon class="h-5 w-5"/>
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center flex-1 p-4">
                        <div>
                            <h2 class="font-bold text-lg">{{ user.name }}</h2>
                            <p class="text-xs text-gray-500">{{ followerCount || 0 }} follower(s)</p>
                        </div>

                        <div v-if="!isMyProfile">
                            <PrimaryButton v-if="!isCurrentUserFollower" @click="followUser">
                                Follow User
                            </PrimaryButton>
                            <DangerButton v-else @click="followUser">
                                Unfollow User
                            </DangerButton>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <TabGroup>
                    <TabList class="flex space-x-1 bg-blue-900/20 bg-white ">
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
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="My Profile" :selected="selected"/>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
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
                        <TabPanel
                            v-if="isMyProfile"
                            class='shadow'>
                            <Edit :mustVerifyEmail="mustVerifyEmail" :status="status"/>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
