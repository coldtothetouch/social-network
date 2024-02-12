<script setup>
import {computed, onMounted, ref, watch} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import {isImage} from '@/helpers.js'
import {XMarkIcon, PaperClipIcon} from "@heroicons/vue/24/outline"
import {useForm} from "@inertiajs/vue3";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    modelValue: Boolean
})

watch(() => props.post, () => {
    form.id = props.post.id
    form.body = props.post.body
})

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', false)
})

/**
 * {
 *     file: File,
 *     url: '',
 * }
 */
const attachmentFiles = ref([])

const emit = defineEmits(['update:modelValue'])

function closeModal() {
    show.value = false
    form.reset()
    attachmentFiles.value = []
}

const form = useForm({
    id: null,
    body: '',
})

async function onAttachmentChoose(event) {
    for (const file of event.target.files) {
        const myFile = {
            file,
            url: await readFile(file)
        }
        attachmentFiles.value.push(myFile)
    }
    event.target.value = null
}

function removeFile(file) {
    attachmentFiles.value = attachmentFiles.value.filter(f => f !== file)
}

async function readFile(file) {
    return new Promise((res, rej) => {
        if (isImage({mime: file.type})) {
            const reader = new FileReader()
            reader.onload = () => {
                res(reader.result)
            }
            reader.onerror = rej
            reader.readAsDataURL(file)
        } else {
            res(null)
        }
    })
}

const editor = ClassicEditor
const editorConfig = {
    toolbar: [
        'heading',
        '|',
        'bold',
        'italic',
        '|',
        'link',
        '|',
        'bulletedList',
        'numberedList',
        '|',
        'outdent',
        'indent',
        '|',
        'blockQuote'
    ]
}

function submit() {
    if (form.id) {
        form.put(route('post.update', props.post), {
            preserveScroll: true,
            onSuccess: () => {
                show.value = false
                form.reset()
            }
        })
    } else {
        form.post(route('post.create'), {
            preserveScroll: true,
            onSuccess: () => {
                show.value = false
                form.reset()
            }
        })
    }
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
                        class="flex min-h-full items-center justify-center p-4 text-center"
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
                                class="w-full max-w-md transform overflow-hidden bg-white text-left align-middle shadow-xl transition-all"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="flex items-center justify-between text-lg font-medium leading-6 text-gray-900 bg-gray-100 p-4"
                                >
                                    {{ post.id ? 'Edit Post' : 'Create Post' }}
                                    <button @click="closeModal" class="hover:bg-gray-200 p-1 rounded-full focus-visible:outline-none">
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>

                                <div class="p-5">
                                    <PostUserHeader :post="post" :show-time="false"/>

                                    <div class="mt-2">
                                        <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"/>
                                        <div class="grid md:grid-cols-2 lg:grid-cols-3 sm:grid-cols-1 gap-3 mt-3">
                                            <template v-for="(myFile, i) of attachmentFiles">
                                                <div class="relative group object-cover">
                                                    <button
                                                        @click="removeFile(myFile)"
                                                        class="absolute opacity-0 group-hover:opacity-100 transition-all right-1 top-1 w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                                        <XMarkIcon class="h-5 w-5"/>
                                                    </button>

                                                    <img v-if="isImage(myFile.file)" :src="myFile.url" class=" rounded-md object-cover aspect-square">

                                                    <div v-else class="bg-gray-100 h-full flex flex-col items-center justify-center text-gray-400 overflow-hidden">
                                                        <PaperClipIcon class="w-12 h-12"/>

                                                        {{ myFile.file.name }}
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex gap-3">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="submit"
                                        >
                                            Save
                                        </button>
                                        <button
                                            type="button"
                                            class="relative inline-flex flex gap-1 items-center justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                        >
                                            <PaperClipIcon class="w-4 h-4"/>
                                            Attach
                                            <input type="file" multiple @change="onAttachmentChoose" class="absolute left-0 right-0 top-0 bottom-0 opacity-0">
                                        </button>
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

