<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({mpesaStkPayments:Array});
</script>

<template>
  <Head title="Mpesa STK Payments" />
<AdminLayout>
  <Table :resource="mpesaStkPayments" />
</AdminLayout>
</template>