<script setup>
import {computed} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'

import {XMarkIcon} from "@heroicons/vue/24/outline"
import {useForm, usePage} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    modelValue: Boolean
})

const page = usePage()

const show = computed({
    get: () => props.modelValue,
    set: () => emit('update:modelValue', false)
})

const emit = defineEmits(['update:modelValue', 'hide', 'groupCreated'])

function closeModal() {
    show.value = false
    emit('hide')
    form.reset()
}

const form = useForm({
    email: '',
})

function submit() {
    form.post(route('groups.invite', page.props.group.slug), {
        onSuccess: (res) => {
            console.log(res);
            closeModal()
        },
        onError: (res) => {
            console.log(res);
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
                                    class="dark:bg-slate-700 dark:text-white flex items-center justify-between text-lg font-medium leading-6 text-gray-900 bg-gray-100 p-4"
                                >
                                    Invite Users
                                    <button @click="closeModal"
                                            class="dark:hover:bg-slate-600 hover:bg-gray-200 p-1 rounded-full focus-visible:outline-none">
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>

                                <div class="dark:bg-slate-800 p-5">
                                    <div class="flex flex-col w-full gap-3 mb-5">
                                        <div>
                                            <InputLabel class="dark:text-white text-xl pb-2">Username or email</InputLabel>
                                            <TextInput
                                                :class="page.props.errors.email ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'dark:bg-slate-700'"
                                                v-model="form.email"/>
                                            <div class="text-red-500">{{page.props.errors.email}}</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-5">
                                        <button @click="submit"
                                                class="dark:bg-slate-800 dark:border-slate-700 dark:hover:bg-slate-700 flex flex-1 justify-center items-center gap-1 border py-2 px-3 bg-indigo-500 text-white rounded-lg hover:bg-indigo-400"
                                        >
                                            Create
                                        </button>
                                        <button @click="closeModal"
                                                class="dark:bg-slate-800 dark:border-slate-700 dark:hover:bg-slate-700 flex flex-1 justify-center items-center gap-1 border py-2 px-3 rounded-lg bg-red-500 text-white hover:bg-red-400"
                                        >
                                            Cancel
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

