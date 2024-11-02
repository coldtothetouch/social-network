<script setup>
import TextareaInput from "@/Components/App/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";

defineProps({
    form: Object
})
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
            <TextareaInput class="w-full" v-model="form.description"/>
        </div>
    </div>
</template>
