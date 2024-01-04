<script setup>

defineProps({
   title : String,
});
</script>

<template>
    <td class="whitespace-nowrap px-4 py-2 text-gray-700 space-x-1">
       {{ title }}
    </td>
</template>