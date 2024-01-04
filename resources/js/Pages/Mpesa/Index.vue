<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({stkPayments:Array});
</script>

<template>
<AdminLayout>
  <Table :resource="stkPayments" />
</AdminLayout>
</template>