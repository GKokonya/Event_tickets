<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({roles:Array});
</script>

<template>
<AdminLayout>
  <Table :resource="roles" />
</AdminLayout>
</template>