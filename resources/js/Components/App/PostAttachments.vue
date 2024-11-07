<script setup>
import {ArrowUpTrayIcon, DocumentIcon} from '@heroicons/vue/24/outline'
import {isImage} from "@/helpers.js";

const props = defineProps({
    attachments: Array
})

defineEmits(['attachmentClick'])

</script>

<template>
    <template v-for="(attachment, i) of props.attachments.slice(0, 4)">
        <div @click="$emit('attachmentClick', i)" class="relative group relative cursor-pointer">

            <div v-if="i === 3 && props.attachments.length > 4"
                 class="absolute top-0 left-0 bottom-0 right-0 flex items-center justify-center text-2xl text-white bg-black/40 rounded-md">
                +{{ attachments.length - 3 }} more...
            </div>

            <a @click.stop :href="route('posts.attachments.download', attachment)"
               class="absolute opacity-0 group-hover:opacity-100 transition-all right-5 top-5 h-10 w-10 bg-gray-400 rounded-lg flex justify-center items-center hover:bg-gray-500">
                <ArrowUpTrayIcon class="text-white w-6 h-6"/>
            </a>

            <img v-if="isImage(attachment)" :src="attachment.url" class="object-cover aspect-square rounded-md"
                 :alt="attachment.name">

            <div v-else
                 class="bg-gray-100 aspect-square flex flex-col items-center justify-center rounded-md text-gray-400">
                <DocumentIcon class="w-40 h-40"/>
                {{ attachment.name }}
            </div>
        </div>
    </template>
</template>
