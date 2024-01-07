<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({order_details:Array});
</script>

<template>
<Head title="Order Details" />
<AdminLayout>
  <Table :resource="order_details" />
</AdminLayout>
</template>