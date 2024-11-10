<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

defineProps({
    form: Object
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

</script>

<template>
    <div class="flex flex-col w-full gap-3 mb-3">
        <div>
            <InputLabel class="dark:text-white text-xl pb-2">Group name</InputLabel>
            <TextInput class="dark:bg-slate-700" v-model="form.name"/>
        </div>
        <div class="flex items-center gap-2 dark:text-white">
            <Checkbox class="dark:bg-slate-700" v-model:checked="form.private"/>
            Private group
        </div>
        <div>
            <InputLabel class="dark:text-white text-xl pb-2">About group</InputLabel>
            <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"/>
        </div>
    </div>
</template>
