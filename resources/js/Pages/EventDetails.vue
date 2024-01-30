<script setup>
import { useForm,Link } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';
import Alert from '@/Components/Alert.vue';
import { isIntegerKey } from '@vue/shared';

const props=defineProps({event : Array, img: String, tickets: Array, cart:Array, min_quantity:Number, max_quantity: Number});

const form = useForm ({
  event_ticket_type_id: null,
  quantity:1
})

function increment(){
  if(form.quantity<3){
    form.quantity++
  }
}

function decrement(){
if(form.quantity>1){
    form.quantity--
  }
}


const error="Error";
const success="Success";

const isSuccess=true;
const title="Event-Details";
</script>

<template>
  <Layout :title="title" :header="title">
  
<section>
  <Alert v-if="$page.props.errors.id" :isSuccess="!isSuccess" :title="error" :message="$page.props.errors.event_ticket_type_id" />
  <Alert v-if="$page.props.flash.cart_success" :title="success" :isSuccess="isSuccess" :message="$page.props.flash.cart_success">
  <Link href="/cart">View Cart</Link>
  </Alert>
  <Alert v-if="$page.props.flash.cart_error" :title="error" :message="$page.props.flash.cart_error" />
  <div v-if="tickets[0]==null" class="relative max-w-screen-xl px-4 py-8 mx-auto">
    <div class="grid items-start grid-cols-1 gap-8 md:grid-cols-2">
      <div class="grid grid-cols-2 gap-4 md:grid-cols-1">No tickets available at the moment</div>
    </div>
  </div>

  <div v-else class="relative max-w-screen-xl px-4 py-8 mx-auto">
    <div class="grid items-start grid-cols-1 gap-8 md:grid-cols-2">
      <div class="grid grid-cols-2 gap-4 md:grid-cols-1">
        <img :alt="event.title" :src="img" class="object-cover w-full aspect-square rounded-xl"/>
      </div>


      <div  class="sticky top-0">
        <div class="flex justify-between mt-2">
           <div class="max-w-[35ch]">
            <div class="flex space-x-2 mt-2">
              <h1 class="text-3xl font-bold">{{ event.title }}</h1>
            </div>

            <div class="flex space-x-2 mt-2">
              <h1 class="font-bold">Venue: </h1>
              <p class="mt-0.5 text-sm text-gray-500 font-bold">{{ event.venue }}</p>
            </div>

            <div class="flex space-x-2 mt-2">
              <h1 class="font-bold">From: </h1>
              <p class="mt-0.5 text-sm text-gray-500 font-bold">{{ event.start_date }}  {{ event.start_time }}</p>
            </div>

            <div class="flex space-x-2 mt-2">
              <h1 class="font-bold">To: </h1>
              <p class="mt-0.5 text-sm text-gray-500 font-bold">{{ event.end_date }}  {{ event.end_time }}</p>
            </div>

            <div class="mt-4">
              <h1>Share this Event</h1>
              <div class="flex space-x-2 mt-2">

                <div class="twitter">
                  <svg height="24px" width="24px" fill="#00AAEC" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-271 296.6 256.4 208.4" xml:space="preserve">
                    <path d="M-14.6,321.2c-9.4,4.2-19.6,7-30.2,8.3c10.9-6.5,19.2-16.8,23.1-29.1c-10.2,6-21.4,10.4-33.4,12.8c-9.6-10.2-23.3-16.6-38.4-16.6c-29,0-52.6,23.6-52.6,52.6c0,4.1,0.4,8.1,1.4,12c-43.7-2.2-82.5-23.1-108.4-55c-4.5,7.8-7.1,16.8-7.1,26.5c0,18.2,9.3,34.3,23.4,43.8c-8.6-0.3-16.7-2.7-23.8-6.6v0.6c0,25.5,18.1,46.8,42.2,51.6c-4.4,1.2-9.1,1.9-13.9,1.9c-3.4,0-6.7-0.3-9.9-0.9c6.7,20.9,26.1,36.1,49.1,36.5c-18,14.1-40.7,22.5-65.3,22.5c-4.2,0-8.4-0.2-12.6-0.7c23.3,14.9,50.9,23.6,80.6,23.6c96.8,0,149.7-80.2,149.7-149.7c0-2.3,0-4.6-0.1-6.8C-30.5,341-21.6,331.8-14.6,321.2"/>
                  </svg>
                </div>
                <div class="facebook">
                  <svg fill="#4460A0" height="24px" width="24px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-143 145 512 512" xml:space="preserve" stroke="#4460A0">
                      <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                      <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                      <g id="SVGRepo_iconCarrier"> <path d="M329,145h-432c-22.1,0-40,17.9-40,40v432c0,22.1,17.9,40,40,40h432c22.1,0,40-17.9,40-40V185C369,162.9,351.1,145,329,145z M169.5,357.6l-2.9,38.3h-39.3v133H77.7v-133H51.2v-38.3h26.5v-25.7c0-11.3,0.3-28.8,8.5-39.7c8.7-11.5,20.6-19.3,41.1-19.3 c33.4,0,47.4,4.8,47.4,4.8l-6.6,39.2c0,0-11-3.2-21.3-3.2c-10.3,0-19.5,3.7-19.5,14v29.9H169.5z"/> </g>
                  </svg>
                </div>

                <div class="whatsapp"></div>
                  <svg fill="#25D366" width="24px" height="24px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <title>whatsapp</title>
                    <path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507l0 0zM16.062 28.228h-0.005c-0 0-0.001 0-0.001 0-2.319 0-4.489-0.64-6.342-1.753l0.056 0.031-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353h-0zM22.838 18.977c-0.371-0.186-2.197-1.083-2.537-1.208-0.341-0.124-0.589-0.185-0.837 0.187-0.246 0.371-0.958 1.207-1.175 1.455-0.216 0.249-0.434 0.279-0.805 0.094-1.15-0.466-2.138-1.087-2.997-1.852l0.010 0.009c-0.799-0.74-1.484-1.587-2.037-2.521l-0.028-0.052c-0.216-0.371-0.023-0.572 0.162-0.757 0.167-0.166 0.372-0.434 0.557-0.65 0.146-0.179 0.271-0.384 0.366-0.604l0.006-0.017c0.043-0.087 0.068-0.188 0.068-0.296 0-0.131-0.037-0.253-0.101-0.357l0.002 0.003c-0.094-0.186-0.836-2.014-1.145-2.758-0.302-0.724-0.609-0.625-0.836-0.637-0.216-0.010-0.464-0.012-0.712-0.012-0.395 0.010-0.746 0.188-0.988 0.463l-0.001 0.002c-0.802 0.761-1.3 1.834-1.3 3.023 0 0.026 0 0.053 0.001 0.079l-0-0.004c0.131 1.467 0.681 2.784 1.527 3.857l-0.012-0.015c1.604 2.379 3.742 4.282 6.251 5.564l0.094 0.043c0.548 0.248 1.25 0.513 1.968 0.74l0.149 0.041c0.442 0.14 0.951 0.221 1.479 0.221 0.303 0 0.601-0.027 0.889-0.078l-0.031 0.004c1.069-0.223 1.956-0.868 2.497-1.749l0.009-0.017c0.165-0.366 0.261-0.793 0.261-1.242 0-0.185-0.016-0.366-0.047-0.542l0.003 0.019c-0.092-0.155-0.34-0.247-0.712-0.434z"></path>
                  </svg>
              </div>
            </div>

          </div>
        </div>

        <details class="group relative mt-4">
          <summary class="block">
            <div>
              <div><p class="font-bold">Description:</p></div>
              <div class="prose max-w-none group-open:hidden">
                <p class="text-sm text-gray-500 font-bold">{{ event.description }}</p>
              </div>
            </div>
          </summary>

        </details>

        <form class="mt-8"  @submit.prevent="form.post(route('cart.store'))">
          <fieldset>
            <legend class="mb-1 text-sm font-medium">Ticket</legend>

            <div class="flow-root">
              <div class="-m-0.5 flex flex-wrap">
                <label :for="ticket.event_ticket_type_id" class="cursor-pointer p-0.5" v-for="ticket in tickets">
                  <input type="radio"  v-model="form.event_ticket_type_id"  name="ticket" :id="ticket.event_ticket_type_id" :value="ticket.id" class="sr-only peer"/>
                  <span class="inline-block px-3 py-1 text-xs font-medium border rounded-full group peer-checked:bg-black peer-checked:text-white">{{ ticket.title }} - KES {{ ticket.unit_price }}</span>
                </label>
              </div>
            </div>
          </fieldset>


          <div class="flex mt-8">
            <div class="flex flex-wrap">
              <label for="Quantity" class="sr-only"> Quantity </label>
              <div class="flex items-center gap-1">
                <button type="button" @click="decrement" class="w-10 h-10 leading-10 text-gray-600 transition hover:opacity-75">&minus;</button>
                <input v-model="form.quantity" :min="min_quantity" :max="max_quantity" type="number" placeholder="1" class="h-10 w-16 rounded border-gray-200 text-center [-moz-appearance:_textfield] sm:text-sm [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none"/>
                <button type="button"  @click="increment" class="w-10 h-10 leading-10 text-gray-600 transition hover:opacity-75">&plus;</button>
              </div>
            </div>


            <button type="submit" class="block px-5 py-3 ml-3 text-xs font-medium text-white bg-gray-600 rounded hover:bg-gray-800">Add to Cart</button>
            <!--
            <button v-if="$page.props.cart.item_count>0" type="submit" class="block px-5 py-3 ml-3 text-xs font-medium text-white bg-gray-600 rounded hover:bg-gray-800">Update</button>
          -->
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

  </Layout>  
</template>
