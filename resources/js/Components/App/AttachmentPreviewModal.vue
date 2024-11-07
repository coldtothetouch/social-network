<script setup>
import {computed} from 'vue'
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {ChevronLeftIcon, ChevronRightIcon, DocumentIcon, XMarkIcon,} from "@heroicons/vue/24/outline"
import {isImage, isVideo} from "@/helpers.js";

const props = defineProps({
    attachments: {
        type: Array,
        required: true,
    },
    index: Number,
    modelValue: Boolean
})

const show = computed({
    get: () => props.modelValue,
    set: () => emit('update:modelValue', false)
})

const emit = defineEmits(['update:modelValue', 'update:index', 'hide'])

function closeModal() {
    show.value = false
    emit('hide')
}

const currentIndex = computed({
    get: () => props.index,
    set: (value) => emit('update:index', value)
})

const attachment = computed(() => {
    return props.attachments[currentIndex.value]
})

function showNext() {
    if (currentIndex.value >= props.attachments.length - 1) return
    currentIndex.value++
}

function showPrevious() {
    if (currentIndex.value <= 0) return
    currentIndex.value--
}

</script>

<template>
    <teleport to="body">
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-10">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/25"/>
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex h-screen w-screen items-center justify-center text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full h-full transform overflow-hidden bg-white text-left align-middle shadow-xl transition-all"
                            >

                                <div class="relative group h-full bg-slate-800">

                                    <button @click="closeModal"
                                            class="hover:bg-black/40 z-20 right-3 top-3 text-white p-1 absolute rounded-full focus-visible:outline-none">
                                        <XMarkIcon class="w-6 h-6"/>
                                    </button>

                                    <div @click="showPrevious"
                                         class="w-16 h-full flex items-center cursor-pointer text-white opacity-0 group-hover:opacity-100 absolute bg-black/20 left-0">
                                        <ChevronLeftIcon class="w-16"/>
                                    </div>

                                    <div @click="showNext"
                                         class="w-16 h-full flex items-center cursor-pointer text-white opacity-0 group-hover:opacity-100 absolute bg-black/20 right-0">
                                        <ChevronRightIcon class="w-16"/>
                                    </div>

                                    <div class="flex items-center justify-center h-full">
                                        <img v-if="isImage(attachment)" :src="attachment.url"
                                             class="max-w-full object-contain max-h-full rounded-md select-none"
                                             :alt="attachment.name">

                                        <video v-else-if="isVideo(attachment)" :src="attachment.url" controls autoplay/>

                                        <div v-else
                                             class="bg-gray-100 p-20 flex flex-col items-center justify-center text-gray-400">
                                            <DocumentIcon class="w-40 h-40"/>
                                            {{ attachment.name }}
                                        </div>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>

