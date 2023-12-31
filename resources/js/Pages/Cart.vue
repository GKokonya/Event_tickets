<script setup>
import {router, useForm,Link,Head } from '@inertiajs/vue3';
import Layout from '../Components/Layout.vue';
import { reactive } from 'vue'

defineProps({
    events:Array,

});
const form = useForm ({
    event_ticket_type_id: null,

})

function destroy(event_ticket_type_id){
    if(confirm('Are you sure?')){
        //Inertia.delete(route('cart.destroy',event_ticket_type_id))
        router.delete(route('cart.destroy', event_ticket_type_id))
    }

}

function incrementQuantity(id,qty){
  let new_quantity=qty+1;
  let data = {
    quantity: new_quantity,
    event_ticket_type_id: id,

  }

  router.post('cart/',data)
}

function decrementQuantity(id,qty){
  let new_quantity=qty-1;
  let data = {
    quantity: new_quantity,
    event_ticket_type_id: id,

  }
  router.post('cart/',data)
}

const title="cart";

</script>

<template>
    <Layout :title="title" :header="title">
      <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mt-4 max-w-2xl md:mt-12">
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-6 sm:px-8 sm:py-10">
              <div class="flow-root">
                <ul class="-my-4">
                  <li v-if="$page.props.cart.item_count==0">
                    <h1 class="font-bold text-2xl">Cart is empty</h1>  
                </li>
                  <li v-else v-for="cart in $page.props.cart.items" :key="cart.event_ticket_type_id" class="flex flex-col space-y-3 py-6 text-left sm:flex-row sm:space-x-5 sm:space-y-0">
                    <div class="shrink-0">
                      <img class="h-24 w-24 max-w-full rounded-lg object-cover" :alt="cart.title" :src="cart.image"/>
                    </div>

                    <div class="relative flex flex-1 flex-col justify-between">
                      <div class="sm:col-gap-5 sm:grid sm:grid-cols-2">
                        <div class="pr-8 sm:pr-5">
                          <p class="text-base font-semibold text-gray-900">Event: {{ cart.event }}</p>
                          <p class="mx-0 mt-1 mb-0 text-sm text-gray-400">{{ cart.ticket_type }}</p>
                        </div>

                        <div class="mt-4 flex items-end justify-between sm:mt-0 sm:items-start sm:justify-end">
                          <p class="shrink-0 w-20 text-base font-semibold text-gray-900 sm:order-2 sm:ml-8 sm:text-right">KES {{cart.unit_price}}.00</p>

                          <div class="sm:order-1">
                            <div class="mx-auto flex h-8 items-stretch text-gray-600">
                              <button @click="decrementQuantity(cart.event_ticket_type_id,cart.quantity)" class="flex items-center justify-center rounded-l-md bg-gray-200 px-4 transition hover:bg-black hover:text-white">-</button>
                              <input class="flex border-none w-full items-center justify-center bg-gray-100 px-4 text-xs uppercase transition" type="text" disabled v-model="cart.quantity"/>
                              <button @click="incrementQuantity(cart.event_ticket_type_id,cart.quantity)" class="flex items-center justify-center rounded-r-md bg-gray-200 px-4 transition hover:bg-black hover:text-white">+</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="absolute top-0 right-0 flex sm:bottom-0 sm:top-auto">
                        <button @click="destroy(cart.event_ticket_type_id)" type="button" class="flex rounded p-2 text-center text-gray-500 transition-all duration-200 ease-in-out focus:shadow hover:text-gray-900">
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" class=""></path>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>

              <div v-if="$page.props.cart.item_count!=0" class="mt-6 border-t border-b py-2">
                <div class="flex items-center justify-between">
                  <p class="text-sm text-gray-400">Subtotal</p>
                  <p class="text-lg font-semibold text-gray-900">KES {{ $page.props.cart.total_price }}</p>
                </div>
                <div class="flex items-center justify-between">
                  <p class="text-sm text-gray-400">Tax(16%)</p>
                  <p class="text-lg font-semibold text-gray-900">KES {{ 0.16*$page.props.cart.total_price}}</p>
                </div>
              </div>
              <div v-if="$page.props.cart.item_count!=0" class="mt-6 flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900">Total</p>
                <p class="text-2xl font-semibold text-gray-900"><span class="text-xs font-normal text-gray-400">KES</span> {{ $page.props.cart.total_price+( Math.round(0.16*$page.props.cart.total_price)) }}</p>
              </div>

              <div v-if="$page.props.cart.item_count!=0" class="mt-6 text-center">
                <Link :href="route('checkout')" class="group inline-flex w-full items-center justify-center rounded-md bg-gray-900 px-6 py-4 text-lg font-semibold text-white transition-all duration-200 ease-in-out focus:shadow hover:bg-gray-800">
                  Checkout
                  <svg xmlns="http://www.w3.org/2000/svg" class="group-hover:ml-8 ml-4 h-6 w-6 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                  </svg>
                </Link>
              </div>

            </div>
          </div>
        </div>
      </div>
  </Layout>  
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
