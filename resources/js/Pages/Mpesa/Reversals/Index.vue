<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({mpesaReversals:Array});
</script>

<template>
  <Head title="Mpesa Reversals" />
<AdminLayout>
  <Table :resource="mpesaReversals" />
</AdminLayout>
</template>