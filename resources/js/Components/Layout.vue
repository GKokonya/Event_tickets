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
    <section class="mx-auto bg-gray-200">
        <slot />
    </section>

    <Footer />
</template>
