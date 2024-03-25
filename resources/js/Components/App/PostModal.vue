<script setup>
import {computed, ref, watch} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import {isImage} from '@/helpers.js'
import {XMarkIcon, PaperClipIcon, ArrowUturnLeftIcon} from "@heroicons/vue/24/outline"
import {useForm, usePage} from "@inertiajs/vue3";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    modelValue: Boolean
})

const allowedAttachmentExtensions = usePage().props.allowedAttachmentExtensions

const computedAttachments = computed(() => {
    if (props.post) {
        return [...attachmentFiles.value, ...(props.post.attachments || [])]
    } else {
        return [...attachmentFiles.value]
    }
})

watch(() => props.post, () => {
    form.body = props.post.body || ''
})

const show = computed({
    get: () => props.modelValue,
    set: () => emit('update:modelValue', false)
})

/**
 * {
 *     file: File,
 *     url: '',
 * }
 */
const attachmentFiles = ref([])

const emit = defineEmits(['update:modelValue', 'hide'])

function closeModal() {
    show.value = false
    emit('hide')
    resetModal()
}

function resetModal()
{
    form.reset()

    showAllowedExtensionsText.value = false
    attachmentFiles.value = []
    attachmentErrors.value = []

    if (props.post.attachments) {
        props.post.attachments.forEach(file => file.deleted = false)
    }
}

const form = useForm({
    body: '',
    attachments: [],
    deleted_file_ids: [],
    _method: 'POST'
})

const showAllowedExtensionsText = ref(false)
async function onAttachmentChoose(event) {
    for (const file of event.target.files) {

        const parts = file.name.split('.')
        const ext = parts.pop().toLowerCase()

        if (!allowedAttachmentExtensions.includes(ext)) {
            showAllowedExtensionsText.value = true
        }

        const myFile = {
            file,
            url: await readFile(file)
        }
        attachmentFiles.value.push(myFile)
    }
    event.target.value = null
}

function removeFile(myFile, i = null) {
    if (myFile.file) {
        attachmentFiles.value = attachmentFiles.value.filter(f => f !== myFile)
    } else {
        form.deleted_file_ids.push(myFile.id)
        myFile.deleted = true
    }

    if (attachmentErrors.value[i]) {
        attachmentErrors.value = attachmentErrors.value.filter((val, ind) => ind !== i)
        showAllowedExtensionsText.value = false
    }
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

const attachmentErrors = ref([])

function submit() {
    form.attachments = attachmentFiles.value.map(file => file.file)
    if (props.post.id) {
        form._method = 'PUT'
        form.post(route('post.update', props.post), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal()
            },
            onError: (errors) => {
                processErrors(errors)
            }
        })
    } else {
        form.post(route('post.create', form.attachments), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal()
            },
            onError: (errors) => {
                processErrors(errors)
            }
        })
    }
}

function processErrors(errors) {
    for (const key in errors) {
        if (key.includes('.')) {
            const [, index] = key.split('.')
            attachmentErrors.value[index] = errors[key]
        }
    }
}

function restoreFile(file) {
    file.deleted = false
    form.deleted_file_ids = form.deleted_file_ids.filter(id => id !== file.id)
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
                                    <button @click="closeModal"
                                            class="hover:bg-gray-200 p-1 rounded-full focus-visible:outline-none">
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>

                                <div class="p-5">
                                    <PostUserHeader :post="post" :show-time="false"/>

                                    <div class="mt-2">
                                        <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"/>

                                        <div v-if="showAllowedExtensionsText"
                                             class="border-l-8 border-amber-300 text-gray-700 bg-amber-100 p-3 mt-3">
                                            Allowed file extensions:
                                            <p class="text-sm">{{ allowedAttachmentExtensions.join(', ') }}</p>
                                        </div>

                                        <div class="grid md:grid-cols-2 lg:grid-cols-3 sm:grid-cols-1 gap-3 mt-3"
                                             :class="computedAttachments.length === 1 ? 'grid-cols-1' : ''">
                                            <div v-for="(myFile, i) of computedAttachments">
                                                <div class="relative group object-cover"
                                                     :class=" attachmentErrors[i] ? 'border-2 border-amber-300' : ''">
                                                    <div v-if="myFile.deleted"
                                                         class="absolute z-10 bottom-0 left-0 right-0 text-sm bg-red-500 py-1 px-2 text-white flex items-center justify-between">
                                                        <p>To be deleted</p>
                                                        <ArrowUturnLeftIcon @click="restoreFile(myFile, i)"
                                                                            class="w-4 h-4 cursor-pointer z-10"/>
                                                    </div>
                                                    <button
                                                        v-if="!myFile.deleted"
                                                        @click="removeFile(myFile, i)"
                                                        class="absolute z-10 opacity-0 group-hover:opacity-100 transition-all right-1 top-1 w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                                        <XMarkIcon class="h-5 w-5"/>
                                                    </button>
                                                    <img v-if="isImage(myFile.file || myFile)" :src="myFile.url"
                                                         class="object-cover aspect-square"
                                                         :class="[myFile.deleted ? 'opacity-50' : '']"
                                                         alt="image">
                                                    <div v-else
                                                         class="bg-gray-100 px-3 aspect-square flex flex-col items-center justify-center text-gray-400 overflow-hidden"
                                                         :class="[myFile.deleted ? 'opacity-50' : '']">
                                                        <PaperClipIcon class="w-12 h-12"/>

                                                        {{ (myFile.file || myFile).name }}
                                                    </div>
                                                </div>
                                                <div class="text-amber-500">{{ attachmentErrors[i] }}</div>
                                            </div>
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
                                            <input type="file" multiple @change="onAttachmentChoose"
                                                   class="absolute left-0 right-0 top-0 bottom-0 opacity-0">
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

