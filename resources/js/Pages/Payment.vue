<script setup>
import { Link,useForm } from '@inertiajs/vue3';
import LayoutVue from '../Components/Layout.vue';

let props=defineProps({events:Array, cart:Array});
const title="M-Pesa";

const form=useForm({

    country_code: 254,
    phone_number: 717149701,
    email: "gbkoks196@gmail.com"

});

</script>

<template>

    <Layout :title="title" :header="title">


        <section>
        <h1 class="sr-only">Checkout</h1>

        <div class="grid grid-cols-1 mx-auto max-w-screen-2xl md:grid-cols-2">
            <div class="py-12 bg-gray-50 md:py-24">
            <div class="max-w-lg px-4 mx-auto space-y-8 lg:px-8">
                <Link class="flex" :href="route('checkout')">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> 
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.7071 5.29289C12.0976 5.68342 12.0976 6.31658 11.7071 6.70711L7.41421 11H19C19.5523 11 20 11.4477 20 12C20 12.5523 19.5523 13 19 13H7.41421L11.7071 17.2929C12.0976 17.6834 12.0976 18.3166 11.7071 18.7071C11.3166 19.0976 10.6834 19.0976 10.2929 18.7071L4.29289 12.7071C3.90237 12.3166 3.90237 11.6834 4.29289 11.2929L10.2929 5.29289C10.6834 4.90237 11.3166 4.90237 11.7071 5.29289Z" fill="#0F1729"/>
                    </svg>

                    <h1 class="ml-1 font-medium text-gray-900">back</h1>
                </Link>

                <div class="justify-between flex" v-for="item in $page.props.cart.items">
                    <div>
                        <p class="text-sm text-gray-600" >Event: {{ item.event }}</p>
                         <p class="mt-1 text-sm text-gray-600" >Qty {{ item.quantity }}, Ticket: {{ item.ticket_type }}</p>
                    </div>

            

                    <div>
                        <p class="text-sm text-gray-600">KES {{ $page.props.cart.total_price }}.00</p> </div>
                        <p v-if="item.quantity>=2" class="mt-1 text-sm text-gray-600" >KES: {{ item.unit_price }} each</p>
                    </div>

                <div>
                <div class="flow-root">
                 
                </div>
                </div>
            </div>
            </div>

            <div class="py-12 bg-white md:py-24">
            <div class="max-w-lg px-4 mx-auto lg:px-8">
                <form class="grid grid-cols-6 gap-4" @submit.prevent="form.post(route('checkout.mpesa.stk.checkout'))">
                    <div class="col-span-6">
                        <a href="https://cdnlogo.com/logo/m-pesa_35816.html"><img class="h-32 w-32" src="https://cdn.cdnlogo.com/logos/m/95/m-pesa.svg"></a>
                    </div>


                    <div class="col-span-3">
                        <label for="FirstName" class="block text-xs font-medium text-gray-700">Country Code</label>
                        <select v-model="form.country_code" name="country_code" class="w-full mt-1 border-gray-200 rounded-md shadow-sm sm:text-sm">
                            <option :value="form.country_code" selected>+{{ form.country_code }}</option>
                        </select>
                    </div>

                <div class="col-span-3">
                    <label for="LastName" class="block text-xs font-medium text-gray-700">Phone number</label>
                    <input id="phone_number" v-model="form.phone_number" placeholder="7xxxxxxx" type="number" class="w-full mt-1 border-gray-200 rounded-md shadow-sm sm:text-sm"/>
                </div>

                <div class="col-span-6">
                    <label for="Email" class="block text-xs font-medium text-gray-700">Email</label>

                    <input type="email" v-model="form.email" class="w-full mt-1 border-gray-200 rounded-md shadow-sm sm:text-sm"/>
                </div>


                <div class="col-span-6">
                    <button
                    class="block w-full rounded-md bg-black p-2.5 text-sm text-white transition hover:shadow-lg"
                    >
                    Pay Now
                    </button>
                </div>
                </form>
            </div>
            </div>
        </div>
        </section>
        
    </Layout>  
</template>
