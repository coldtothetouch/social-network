<script setup>
import {computed, onMounted, ref, watch} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import TextareaInput from "@/Components/App/TextareaInput.vue";
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import {XMarkIcon} from "@heroicons/vue/24/outline"
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

const emit = defineEmits(['update:modelValue'])

function closeModal() {
    show.value = false
}

const form = useForm({
    id: null,
    body: '',
})

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
    form.patch(route('post.update', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            show.value = false
        }
    })
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
                                    Edit Post
                                    <button @click="closeModal" class="hover:bg-gray-200 p-1 rounded-full">
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>

                                <div class="p-5">
                                    <PostUserHeader :post="post" :show-time="false"/>

                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            <ckeditor :editor="editor" v-model="form.body"
                                                      :config="editorConfig"></ckeditor>
                                            <!--                                            <TextareaInput v-model="form.body" class="w-full" rows="5"></TextareaInput>-->
                                        </p>
                                    </div>

                                    <div class="mt-4">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="submit"
                                        >
                                            Save
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

