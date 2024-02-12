<script setup>
import {onMounted, ref} from 'vue';

const model = defineModel({
    type: String,
    required: false,
});

const props = defineProps({
    placeholder: String,
    autoresize: {
        type: Boolean,
        default: true
    }
})

function adjustHeight() {
    if (props.autoresize) {
        input.value.style.height = 'auto'
        input.value.style.height = input.value.scrollHeight + 'px'
    }
}

onMounted(() => {
    adjustHeight()
})

const emit = defineEmits(['update:modelValue'])

const input = ref(null);

function onInputChange($event) {
    emit('update:modelValue', $event.target.value)
    adjustHeight()
}

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <textarea
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        @input="onInputChange"
        ref="input"
        :placeholder="placeholder"
    ></textarea>
</template>
