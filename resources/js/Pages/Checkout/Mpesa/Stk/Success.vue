<script setup>
//import { Head, Link } from '@inertiajs/vue3';

import Layout from '../../../../Components/Layout.vue';
const title='success';

</script>

<template>
    <Layout :title="title" :header="title">
        <!-- EventList Section -->
        <section>
          <div class="bg-emerald-500 mx-auto py-2 px-3 text-white rounded">
            your order has been completed
          </div>
     </section>
    </Layout>  
</template>
