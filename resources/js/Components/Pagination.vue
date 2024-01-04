<template>
    <div class="flex mx-1">
        <div  v-for="link in links">
            <Link v-if="link.url" v-html="link.label" :href="link.url" class="item-center justify-center px-3 py-2 text-sm rounded-lg text-gray-600" :class="{'bg-gray-200':link.active}" />
            <span v-else v-html="link.label" class="cursor-not-allowed item-center justify-center px-3 py-2 text-sm rounded-lg text-gray-600"></span>
        </div>
    </div>
</template> 


<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
    links:Array
    });

    
//
</script>
