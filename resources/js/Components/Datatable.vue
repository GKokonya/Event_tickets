<script setup>
import { router,Link } from '@inertiajs/vue3';
import { ref  } from 'vue';
import Pagination from '@/Components/Pagination.vue';
</script>

<template>
    <div class="flex flex-col mt-3">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow border-b border-gray-200 relative">
                    <table class="min-w-full divide-y divide-gray-200 bg-white">

                            <slot />

                    </table>
                </div>
            </div>
        </div>
    </div>
</template>