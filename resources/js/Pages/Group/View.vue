<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from '@headlessui/vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed, ref} from "vue";
import {CheckIcon} from "@heroicons/vue/24/outline"
import {CameraIcon, XMarkIcon} from "@heroicons/vue/24/solid"
import DangerButton from "@/Components/DangerButton.vue";
import InviteUserModal from "@/Components/App/InviteUserModal.vue";
import UserListItem from "@/Components/App/UserListItem.vue";
import TextInput from "@/Components/TextInput.vue";
import GroupForm from "@/Components/App/GroupForm.vue";
import PostList from "@/Components/App/PostList.vue";
import CreatePost from "@/Components/App/CreatePost.vue";
import AttachmentPreviewModal from "@/Components/App/AttachmentPreviewModal.vue";

const user = usePage().props.auth.user
const coverImageSrc = ref('')
const avatarImageSrc = ref('')
const showFlash = ref(true)

const props = defineProps({
    group: {
        type: Object,
    },
    posts: Object,
    photos: Array,
    status: {
        type: String,
    },
    errors: Object
});

const ImagesForm = useForm({
    cover: null,
    avatar: null,
});

const currentUserIsAdmin = computed(() => props.group.role === 'admin')
const isJoinedGroup = computed(() => !!props.group.role && props.group.status === 'approved')

const search = ref('')

const showInviteUseModal = ref(false)

const aboutForm = useForm({
    name: props.group.name,
    private: !!props.group.private,
    description: props.group.description,
})

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
    ImagesForm.post(route('groups.updateImage', props.group.slug), {
        onSuccess: () => {
            setTimeout(() => {
                showFlash.value = false
            }, 3000)
            resetCoverImage()
            showFlash.value = true
        },
        preserveScroll: true
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
    ImagesForm.post(route('groups.updateImage', props.group.slug), {
        onSuccess: () => {
            setTimeout(() => {
                showFlash.value = false
            }, 3000)
            resetAvatarImage()
            showFlash.value = true
        },
        preserveScroll: true
    })
}

function resetAvatarImage() {
    avatarImageSrc.value = null
    ImagesForm.avatar = null
}

function joinToGroup() {
    const form = useForm({})
    form.post(route('groups.join', props.group.slug), {
        preserveScroll: true
    })
}

function approveUser(user) {
    const form = useForm({
        user_id: user.id,
        action: 'approve'
    })
    form.post(route('groups.users.approve', props.group), {
        preserveScroll: true
    })
}

function rejectUser(user) {
    const form = useForm({
        user_id: user.id,
        action: 'reject'
    })
    form.post(route('groups.users.approve', props.group), {
        preserveScroll: true
    })
}

function changeRole(user, role) {
    const form = useForm({
        user_id: user.id,
        role: role,
    })
    form.post(route('groups.role.change', props.group), {
        preserveScroll: true
    })
}

function updateGroup() {
    aboutForm.patch(route('groups.update', props.group), {
        preserveScroll: true,
    })
}

function kickUser(user) {
    if (!window.confirm(`Are you sure you want to kick user ${user.name}`)) {
        return false
    }

    const form = useForm({
        user_id: user.id,
    })

    form.delete(route('groups.users.kick', props.group), {
        preserveScroll: true
    })
}

function leaveGroup() {
    const form = useForm({
        user_id: user.id
    })

    form.delete(route('groups.leave', props.group))
}

const showPreviewModal = ref(false)
const previewIndex = ref(null)

function openPreviewModal(index) {
    previewIndex.value = index
    showPreviewModal.value = true
}
</script>

<template>
    <InviteUserModal v-model="showInviteUseModal"/>
    <AuthenticatedLayout>
        <AttachmentPreviewModal
            :attachments="photos"
            v-model:index="previewIndex"
            v-model="showPreviewModal"
        />
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

                <img alt="cover" :src="coverImageSrc || group.cover_path"
                     class="w-full object-cover h-[300px]">
                <div class="absolute top-3 right-3" v-show="currentUserIsAdmin">
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
                        <img alt="avatar" :src="avatarImageSrc  || group.avatar_path"
                             class="w-full h-full object-cover rounded-full">
                        <button
                            v-show="currentUserIsAdmin"
                            v-if="!avatarImageSrc"
                            class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100">
                            <CameraIcon class="w-8 h-8"/>

                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                   @change="onAvatarChange"/>
                        </button>
                        <div v-show="currentUserIsAdmin" v-else class="absolute top-1 right-0 flex flex-col gap-2">
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
                            <h2 class="font-bold text-lg">{{ group.name }}</h2>
                            <p class="text-xs text-gray-500">{{ props.group.follower_count || 0 }} follower(s)</p>
                        </div>

                        <div class="flex gap-2">
                            <PrimaryButton v-if="group.private && !group.role" @click="joinToGroup">
                                Request to join
                            </PrimaryButton>
                            <PrimaryButton v-if="group.private && group.status === 'pending'"
                                           class="!bg-gray-400 cursor-not-allowed">
                                Request sent
                            </PrimaryButton>
                            <PrimaryButton v-if="!group.private && !group.role" @click="joinToGroup">
                                Join to group
                            </PrimaryButton>

                            <PrimaryButton @click="showInviteUseModal = true" v-if="currentUserIsAdmin">
                                Invite users
                            </PrimaryButton>

                            <PrimaryButton v-if="group.private && group.status === 'rejected'"
                                           class="cursor-default !bg-gray-400 hover:bg-gray-400">
                                Request Rejected
                            </PrimaryButton>

                            <DangerButton
                                @click="leaveGroup"
                                v-if="group.role && group.role !== 'admin' && group.status !== 'pending' && group.status !== 'rejected'">
                                Leave group
                            </DangerButton>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <TabGroup>
                    <TabList class="flex space-x-1 bg-blue-900/20 bg-white">
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Posts" :selected="selected"/>
                        </Tab>
                        <Tab v-if="isJoinedGroup" v-slot="{ selected }" as="template">
                            <TabItem text="Users" :selected="selected"/>
                        </Tab>
                        <Tab v-if="currentUserIsAdmin" v-slot="{ selected }" as="template">
                            <TabItem text="Requests" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Photos" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="About" :selected="selected"/>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel>
                            <CreatePost v-if="group.status === 'approved'" :group="group"/>
                            <PostList v-if="!group.private || group.status === 'approved'" :posts="posts.data"/>
                            <div v-else class="text-xl text-center mt-10">You don't have permission to view this content</div>
                        </TabPanel>
                        <TabPanel v-if="isJoinedGroup">
                            <TextInput :model-value="search" placeholder="Type to search" class="w-full my-2"/>
                            <div class="grid grid-cols-2 gap-2">
                                <UserListItem
                                    :disable-user-role-dropdown="group.user_id === user.id"
                                    :show-user-role-dropdown="currentUserIsAdmin"
                                    @role-change="changeRole"
                                    @user-kick="kickUser"
                                    class="bg-white shadow-md"
                                    v-for="user in group.users" :user="user" :key="user.id"/>
                            </div>
                        </TabPanel>
                        <TabPanel v-if="currentUserIsAdmin">
                            <div v-if="group.pending_users.length" class="grid grid-cols-2 gap-2">
                                <UserListItem @reject="rejectUser(user)"
                                              @approve="approveUser(user)"
                                              class="bg-white shadow-md hover:border-2 hover:border-indigo-500 hover:bg-white"
                                              v-for="user in group.pending_users" :user="user" :for-approve="true"
                                              :key="user.id"/>

                            </div>
                            <div v-else class="text-center mt-10 text-xl">
                                No pending users
                            </div>
                        </TabPanel>
                        <TabPanel
                            class='shadow bg-white p-5'>
                            <div class="flex flex-wrap gap-3">
                                <img @click="openPreviewModal(i)" class="size-[300px] rounded-lg" v-for="(photo, i) in props.photos" :src="photo.url" alt="">
                            </div>
                        </TabPanel>
                        <TabPanel
                            class='shadow bg-white p-5'>
                            <template v-if="currentUserIsAdmin">
                                <GroupForm :form="aboutForm"/>
                                <PrimaryButton @click="updateGroup">Submit</PrimaryButton>
                            </template>
                            <div class="ck-content-output" v-html="group.description" v-else/>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
