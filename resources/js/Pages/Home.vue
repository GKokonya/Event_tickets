<script setup>
import Layout from '../Components/Layout.vue';
import EventList from '../Components/EventList.vue';

defineProps({events:Array});
const title="Home";
</script>

<template>
    <Layout :title="title" :header="title">
        <!-- EventList Section -->
        <EventList :events="events"/>
    </Layout>  
</template>
