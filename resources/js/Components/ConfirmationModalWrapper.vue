<template>
    <ConfirmationModal :show="state.show">
        <template #title>
            {{ state.title }}
        </template>
        <template #content>
            {{state.message}}
        </template>

        <template #footer>
            <SecondaryButton @click="cancel" ref="cancelButtonRef">Cancel</SecondaryButton>
            <PrimaryButton class="ml-3" @click="confirm">Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<script setup>
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/Utilities/Composables/useConfirm.js";
import {nextTick, ref, watchEffect} from "vue";

const cancelButtonRef = ref(null);
const {state, confirm, cancel} = useConfirm();

watchEffect(async ()=> {
    if(state.show) {
        await nextTick();
        cancelButtonRef.value?.$el.focus();
    }
})
</script>
