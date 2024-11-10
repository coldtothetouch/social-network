<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";

const props = defineProps({
    content: String
})

const maxContentLength = 255
</script>

<template>
    <Disclosure v-slot="{ open }">
        <template v-if="content.length > maxContentLength">

            <div v-if="!open" class="ck-content-output" v-show="!open" v-html="content.substring(0, maxContentLength)+'...'"/>

            <DisclosurePanel>
                <div class="ck-content-output" v-html="content"/>
            </DisclosurePanel>

            <div class="flex justify-end">
                <DisclosureButton>
                    <p class="dark:text-white cursor-pointer text-blue-600 hover:underline my-2">{{ open ? 'Hide' : 'Read More' }}</p>
                </DisclosureButton>
            </div>

        </template>
        <div v-else class="ck-content-output" v-html="content"/>
    </Disclosure>
</template>
