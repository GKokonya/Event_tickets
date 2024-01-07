<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({stripePayments:Array});
</script>

<template>
<Head title="Sripe Payments" />
<AdminLayout>
  <Table :resource="stripePayments" />
</AdminLayout>
</template>