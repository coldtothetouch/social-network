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
import {useForm} from "@inertiajs/vue3";
import GroupForm from "@/Components/App/GroupForm.vue";

const props = defineProps({
    modelValue: Boolean
})

const show = computed({
    get: () => props.modelValue,
    set: () => emit('update:modelValue', false)
})

const form = useForm({
    name: '',
    private: false,
    description: '',
})

const emit = defineEmits(['update:modelValue', 'hide', 'groupCreated'])

function closeModal() {
    show.value = false
    emit('hide')
    form.reset()
}

function submit() {
    axios.post(route('groups.store'), form).then(({data}) => {
        closeModal()
        emit('groupCreated', data)
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
                                    class="dark:text-white dark:bg-slate-700 flex items-center justify-between text-lg font-medium leading-6 text-gray-900 bg-gray-100 p-4"
                                >
                                    Create new group
                                    <button @click="closeModal"
                                            class="dark:hover:bg-slate-600 hover:bg-gray-200 p-1 rounded-full focus-visible:outline-none">
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>

                                <div class="p-5 dark:bg-slate-800">
                                    <GroupForm :form="form"/>
                                    <div class="flex gap-5">
                                        <button @click="submit"
                                                class="dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 flex flex-1 justify-center items-center gap-1 border py-2 px-3 bg-indigo-500 text-white rounded-lg hover:bg-indigo-400"
                                        >
                                            Create
                                        </button>
                                        <button @click="closeModal"
                                                class="dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 flex flex-1 justify-center items-center gap-1 border py-2 px-3 rounded-lg bg-red-500 text-white hover:bg-red-400"
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

