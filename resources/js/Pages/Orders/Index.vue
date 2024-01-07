<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router,Link } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({orders:Array});
</script>

<template>
<Head title="Orders" />
<AdminLayout>
  <Table :resource="orders">
    <template #cell(actions)="{ item: order }">
      <Link as="button" class="bg-gray-900 text-sm text-white rounded-lg p-2" :href="`/order-details/${order.id}`">
        View order
      </Link>
    </template>
  </Table>

</AdminLayout>
</template>