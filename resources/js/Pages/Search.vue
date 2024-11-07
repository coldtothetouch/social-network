<script setup>

import UserListItem from "@/Components/App/UserListItem.vue";
import GroupItem from "@/Components/App/GroupItem.vue";
import PostList from "@/Components/App/PostList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    users: Array,
    groups: Array,
    posts: Object,
})

</script>

<template>
    <AuthenticatedLayout>
        <div class="p-4 h-full flex flex-col">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="p-3 shadow bg-white rounded ">
                    <h2 class="text-lg font-bold">Users</h2>
                    <div class="grib-cols-2">
                        <UserListItem v-if="users.length" v-for="user in users" :user="user"/>
                        <div v-else>No users were found</div>
                    </div>
                </div>
                <div class="p-3 shadow bg-white rounded ">
                    <h2 class="text-lg font-bold">Groups</h2>
                    <GroupItem v-if="groups.length" v-for="group in groups" :group="group"/>
                    <div v-else>No groups were found</div>
                </div>
            </div>
            <div class="p-3 h-full flex flex-col shadow bg-white rounded overflow-hidden">
                <h2 class="text-lg font-bold">Posts</h2>
                <PostList class="flex-1" :posts="posts"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
