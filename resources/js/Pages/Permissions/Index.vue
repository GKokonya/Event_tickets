<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({permissions:Array});
</script>

<template>
  <Head title="Permissions" />
<AdminLayout>
  <Table :resource="permissions" />
</AdminLayout>
</template>