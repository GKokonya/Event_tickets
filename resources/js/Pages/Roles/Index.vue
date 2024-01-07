<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";
import { Head } from '@inertiajs/vue3';


defineProps(["roles"])
</script>

<template>
<Head title="Roles" />
<AdminLayout>
  <Table :meta="roles" :striped="true">

    <template v-slot:tableGlobalSearch="slotProps">
      <input
        placeholder="Custom Global Search Component..."
        @input="slotProps.onChange($event.target.value)"
      />
    </template>

    <template #head v-slot:head="slotProps">

    </template>

    <template #body  v-slot:body="slotProps">
      <tr v-for="(role, key) in roles.data" :key="role.id" class="hover:bg-gray-50">
        <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrap">{{ role.id }}</td>
        <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrap">{{ role.name }}</td>
        <td class="text-sm py-4 px-2 text-gray-500 whitespace-nowrap" v-for="(permission, index) in role.permissions" :key="index">
          <span class="rounded-xl bg-blue-300 px-2 py-1 text-xs text-blue-700">{{ permission.name}}</span>
        </td>
      </tr>
    </template>
  </Table>
</AdminLayout>
</template>

