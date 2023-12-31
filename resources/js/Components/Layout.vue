<script setup>
import Footer from '../Components/Footer.vue';
import { Head } from '@inertiajs/vue3';
import Nav from '../Components/Nav.vue';

defineProps({
    title:String,
    header: String
});
</script>

<template>
    <Head :title="title" />
    <Nav />

    <!-- Text Header -->
    
    <header class="w-full mx-auto bg-slate-100">
        <div class="flex flex-col items-center py-4">
            <h1 class="font-bold text-gray-800 uppercase hover:text-gray-700 text-5xl" v-text="header"></h1>
        </div>
    </header>

    <div class="mx-auto flex flex-wrap bg-gray-200">
        <div class="px-4 py-4 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8 lg:py-4">
            <div class="flex px-4 py-2">
                <slot />
            </div>
        </div>
    </div>

    <Footer />
</template>
