<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
;
import { Head,useForm,router,Link } from '@inertiajs/vue3';
import Datatable from '@/Components/Datatable.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({orders:Array});
let columns=['id','customer_email','original_total_price', 'refunded_amount','final_total_price','status','mpesa_stk_checkout_id','stripe_checkout_id','payment_type','actions']

let actions={
  
  'details':'/order-details/',
  'refund':'/orders/refund/'
  }
</script>

<template>
  <Head title="Orders" />
  <AdminLayout>
      <Datatable :columns="columns">
        <thead class="bg-gray-50">
          <tr class="font-medium text-xs uppercase text-left tracking-wider text-gray-500 py-3 px-6">
            <th class="" >
                <button class="py-3 px-6 w-full" dusk="sort-venue"><span class="flex flex-row items-center">
                    <input type="checkbox"  @click="checkAll()" v-model="isCheckAll" class="flex flex-row items-center rounded-md border-gray-300 shadow-sm"/>
                    <svg aria-hidden="true" class="w-3 h-3 ml-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" sorted="false">
                        <path fill="currentColor" d="M41 288h238c21.4 0 32.1 25.9 17 41L177 448c-9.4 9.4-24.6 9.4-33.9 0L24 329c-15.1-15.1-4.4-41 17-41zm255-105L177 64c-9.4-9.4-24.6-9.4-33.9 0L24 183c-15.1 15.1-4.4 41 17 41h238c21.4 0 32.1-25.9 17-41z"></path><!----><!---->
                    </svg>
                    </span>
                </button>
            </th>

            <th class="" v-for="(column,index) in columns" :key="index">
                <button class="py-3 px-6 w-full" dusk="sort-venue"><span class="flex flex-row items-center">
                    <span class="uppercase">{{column}}</span>
                    <svg aria-hidden="true" class="w-3 h-3 ml-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" sorted="false">
                        <path fill="currentColor" d="M41 288h238c21.4 0 32.1 25.9 17 41L177 448c-9.4 9.4-24.6 9.4-33.9 0L24 329c-15.1-15.1-4.4-41 17-41zm255-105L177 64c-9.4-9.4-24.6-9.4-33.9 0L24 183c-15.1 15.1-4.4 41 17 41h238c21.4 0 32.1-25.9 17-41z"></path><!----><!---->
                    </svg>
                    </span>
                </button>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200" v-if="orders">                
          <tr class="hover:bg-gray-50"  v-for="(key,index) in orders" :key="index">
            <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrapflex justify-end">
              <input type="checkbox" :id="key.id" ref="id" :value="key.id" class="rounded-md border-gray-300 shadow-sm"/>
            </td>
            <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrapflex justify-end" v-for="(value,index) in key" :key="index">{{value}}</td>
            <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrapflex justify-end" v-for="(action,index) in actions" :key="index">
                <Link class="bg-gray-900 text-sm text-white rounded-lg p-2" :href="action+key.id">
                    {{ index }}
                </Link>
            </td>
          </tr>
        </tbody>
        <tbody class="bg-white divide-y divide-gray-200" v-if="!orders">                
          <tr class="hover:bg-gray-50">
            <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrapflex justify-end">
              No records ...
            </td>
          </tr>
        </tbody>   
      </Datatable>

        <!--pagination-->
        <Pagination :links="orders.links" class="mt-2 flex justify-center"/>
    </AdminLayout>
</template>