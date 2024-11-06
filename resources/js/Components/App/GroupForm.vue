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
            <InputLabel>Group name</InputLabel>
            <TextInput v-model="form.name"/>
        </div>
        <div class="flex items-center gap-2">
            <Checkbox v-model:checked="form.private"/>
            Private group
        </div>
        <div>
            <InputLabel>About group</InputLabel>
            <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"/>
        </div>
    </div>
</template>
