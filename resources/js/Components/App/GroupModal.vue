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
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextareaInput from "@/Components/App/TextareaInput.vue";

const props = defineProps({
    modelValue: Boolean
})

const show = computed({
    get: () => props.modelValue,
    set: () => emit('update:modelValue', false)
})

const emit = defineEmits(['update:modelValue', 'hide'])

function closeModal() {
    show.value = false
    emit('hide')
    form.reset()
}

const form = useForm({
    name: '',
    private: false,
    description: '',
})

function submit() {
    axios.post(route('group.store'), form).then(() => closeModal())
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
                                    Create new group
                                    <button @click="closeModal"
                                            class="hover:bg-gray-200 p-1 rounded-full focus-visible:outline-none">
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>

                                <div class="p-5">
                                    <div class="flex flex-col w-full gap-3 mb-3">
                                        <div>
                                            <InputLabel>Group name</InputLabel>
                                            <TextInput v-model="form.name"/>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <Checkbox v-model:checked="form.private"/>
                                            Private group
                                        </div>
                                        <div>
                                            <InputLabel>About group</InputLabel>
                                            <TextareaInput class="w-full" v-model="form.description"/>
                                        </div>
                                    </div>
                                    <div class="flex gap-5">
                                        <button @click="submit"
                                                class="flex flex-1 justify-center items-center gap-1 border py-2 px-3 bg-indigo-500 text-white rounded-lg hover:bg-indigo-400"
                                        >
                                            Create
                                        </button>
                                        <button @click="closeModal"
                                                class="flex flex-1 justify-center items-center gap-1 border py-2 px-3 rounded-lg bg-red-500 text-white hover:bg-red-400"
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

