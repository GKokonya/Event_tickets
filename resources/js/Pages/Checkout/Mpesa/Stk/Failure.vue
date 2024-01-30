<script setup>

import Layout from '@/Components/Layout.vue';


const title="Cancel";
</script>

<template>
    <Layout :title="title" :header="title">
        <!-- EventList Section -->
        <section>
            <div  class="bg-red-500 mx-auto py-2 px-3 text-white rounded">
                Order Failed
            </div>
        </section>
    </Layout>  
</template>
