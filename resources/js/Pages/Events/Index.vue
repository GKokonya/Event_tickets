<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router,Link } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({events:Array});
</script>

<template>
  <Head title="Events" />
<AdminLayout>
  <Link :href="route('events.create')" Class="mb-4 items-center justify-center rounded-md bg-gray-900 px-2 py-2 text-lg font-semibold text-white transition-all duration-200 ease-in-out focus:shadow hover:bg-gray-800" as="button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
</svg>
</Link>
  <Table :resource="events" />
</AdminLayout>
</template>