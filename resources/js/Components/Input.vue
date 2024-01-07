<script setup>
defineProps({
type:String, placeholder: String
});
</script>

<template>
    <input :type="type" :placeholder="placeholder" />
</template>
