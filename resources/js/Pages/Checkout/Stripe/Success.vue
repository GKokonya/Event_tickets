<script setup>
//import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';

defineProps({customer:Array});
const title="Success";
</script>

<template>
    <Layout :title="title" :header="title">
        <!-- EventList Section -->
        <section>
          <div class="bg-emerald-500 mx-auto py-2 px-3 text-white rounded">
            {{customer.name}}, your order has been completed
          </div>
     </section>
    </Layout>  
</template>
