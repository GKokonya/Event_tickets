<script setup>

// import { Link } from '@inertiajs/vue3';
import Pagination from '../Components/Pagination.vue';
defineProps({
   headers : Array,
   table : Array,
   links: Array
});


</script>

<template>

    <div class="overflow-hidden overflow-x-auto rounded-lg border border-gray-200 my-5">    
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th v-for="header in headers" class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">
                        <div class="flex items-center gap-2">{{ header }}</div>
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
            <slot />
            </tbody>
        </table>
    </div>
    <!--pagination-->
    <Pagination :links="links" class="mt-2 flex justify-center"/>
</template>