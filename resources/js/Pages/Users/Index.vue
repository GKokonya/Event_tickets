<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";
import { Head } from '@inertiajs/vue3';


defineProps(["users"])
</script>

<template>
<Head title="Users" />
<AdminLayout>
  <Table :meta="users">

    <template v-slot:tableGlobalSearch="slotProps">
      <input
        placeholder="Custom Global Search Component..."
        @input="slotProps.onChange($event.target.value)"
      />
    </template>

    <template #head v-slot:head="slotProps">

    </template>

    <template #body  v-slot:body="slotProps">
      <tr v-for="(user, key) in users.data" :key="user.id" class="hover:bg-gray-50">
        <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrap">{{ user.id }}</td>
        <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrap">{{ user.name }}</td>
        <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrap">{{ user.email }}</td>
        <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrap" v-for="(role, index) in user.roles" :key="index">
          <span class="rounded-xl bg-blue-300 px-2 py-1 text-xs text-blue-700">{{ role.name }}</span>
        </td>
      </tr>
    </template>
  </Table>
</AdminLayout>
</template>

