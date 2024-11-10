<script setup>
import {nextTick, onMounted, ref, watch} from 'vue';

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
        input.value.style.height = input.value.scrollHeight + 2 + 'px'
    }
}

onMounted(() => {
    adjustHeight()
})

const emit = defineEmits(['update:modelValue'])

const input = ref(null);

watch(() => props.modelValue, () => {
    nextTick(() => {
        adjustHeight()
    })
})

function onInputChange($event) {
    emit('update:modelValue', $event.target.value)
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
        class="dark:border-slate-600 border-r-none border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        @input="onInputChange"
        ref="input"
        :placeholder="placeholder"
    ></textarea>
</template>
