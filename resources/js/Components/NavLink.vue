<template>
    <Link :href=href class="text-gray-900 hover:underline" :class="{'font-bold underline':active}">
        <slot/>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
    active:Boolean,
    href:String
    });
</script>
