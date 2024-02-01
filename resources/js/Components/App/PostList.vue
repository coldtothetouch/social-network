<script setup>

import PostItem from "@/Components/App/PostItem.vue";

const post1 = {
    user: {
        id: 1,
        name: 'Pavel Durov',
        avatar: 'https://randomuser.me/api/portraits/men/27.jpg',
    },
    group: null,
    created_at: '2023-1-08 21:00',
    attachments: [
        {
            id: 1,
            url: 'https://picsum.photos/1000',
            name: 'test1.png',
            mime: 'image/png',
        },
        {
            id: 2,
            url: 'https://picsum.photos/1000',
            name: 'test2.png',
            mime: 'image/png',
        },
        {
            id: 3,
            url: '#',
            name: 'MyDocument.docx',
            mime: 'application/msword',
        },
    ],
    body: `<p>Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum</p>
           <p>Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum</p>`
}

const post2 = {
    user: {
        id: 2,
        name: 'Elon Musk',
        avatar: 'https://randomuser.me/api/portraits/men/3.jpg',
    },
    group: {
        name: 'SpaceX',
    },
    created_at: '2023-13-04 12:00',
    body: `<p>Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum </p>
           <p>Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum </p>`
}

</script>

<template>
    <div class="flex flex-col flex-1 gap-3 h-full overflow-y-auto">
        <PostItem :post="post1"/>
        <PostItem :post="post2"/>
        <PostItem :post="post2"/>
        <PostItem :post="post2"/>
        <PostItem :post="post2"/>
    </div>
</template>

<style scoped>

</style>
