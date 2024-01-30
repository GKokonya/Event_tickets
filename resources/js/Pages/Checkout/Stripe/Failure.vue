<script setup>

import Layout from '@/Components/Layout.vue';

defineProps({checkout_failure_message:String});
const title="Cancel";
</script>

<template>
    <Layout :title="title" :header="title">
        <!-- EventList Section -->
        <section>
            <div v-if="checkout_failure_message!=null" class="bg-red-500 mx-auto py-2 px-3 text-white rounded">
                {{checkout_failure_message}}
            </div>
        </section>
    </Layout>  
</template>
