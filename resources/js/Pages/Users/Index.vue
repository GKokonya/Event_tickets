<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";
import { Head } from '@inertiajs/vue3';


defineProps(["users"])
</script>

<template>
<Head title="Users" />
<AdminLayout>
  <Table :resource="users" />
</AdminLayout>
</template>