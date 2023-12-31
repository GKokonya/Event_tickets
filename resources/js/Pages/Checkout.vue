<script setup>

import Layout from '../Components/Layout.vue';
import { Link,useForm } from '@inertiajs/vue3';


let props= defineProps({events:Array, stripe_checkout_session: String, payment_methods: Array});

const title="Checkout";

const form=useForm({
    
})

</script>

<template>
    <Layout :header="title" :title="title">
        <section>
            <h1 class="sr-only">Checkout</h1>

            <div class="grid grid-cols-1 mx-auto max-w-screen-2xl md:grid-cols-2">
                <div class="py-12 bg-gray-50 md:py-24 px-20">
                <div class="max-w-lg px-4 mx-auto space-y-8 lg:px-8">
                    <div class="flex items-center">
                    <span class="w-10 h-10 bg-blue-700 rounded-full"></span>

                    <h2 class="ml-4 font-medium text-gray-900">Your Order</h2>
                    </div>

                    <div>
                    <p class="text-2xl font-medium tracking-tight text-gray-900">
                       KES {{ $page.props.cart.total }}
                    </p>

                    <p class="mt-1 text-sm text-gray-600">For the purchase of</p>
                    </div>

                    <div>
                    <div class="flow-root">
                        <ul class="-my-4 divide-y divide-gray-100">
                        <li class="flex items-center py-4" v-for="cart in $page.props.cart.items" :key="cart.event_ticket_type_id">
                            <img
                            :src="cart.image"
                            alt=""
                            class="object-cover w-16 h-16 rounded"
                            />

                            <div class="ml-4">
                            <h3 class="text-sm text-gray-900">{{ cart.event }}</h3>

                            <dl class="mt-0.5 space-y-px text-[10px] text-gray-600">
                                <div>
                                    <dt class="inline">Ticket: </dt>
                                    <dd class="inline">{{ cart.ticket_type }}</dd>
                                </div>

                                <div>
                                    <dt class="inline">Price(Ksh): </dt>
                                    <dd class="inline">{{cart.price}}</dd>
                                </div>
                            </dl>
                            </div>
                        </li>
                        </ul>
                    </div>
                    </div>
                </div>
                </div>

                <div class="py-12 bg-white md:py-24">
                    <div class="max-w-lg px-4 mx-auto lg:px-8">
                        <div class="grid grid-cols-6 gap-4">

                            <div class="col-span-6">
                                <Link :href="route('checkout.mpesa.stk.stk')" class="text-center block w-full rounded-md bg-black p-4 text-sm text-white transition hover:shadow-lg">
                                    Pay with M-Pesa ({{ $page.props.cart.currency }} {{ $page.props.cart.total_price }} )
                                </Link>
                            </div>

                            <form class="col-span-6"  @submit.prevent="form.post(route('checkout.stripe'))">
                                <button class="block w-full text-center `rounded-md bg-black p-4 text-sm text-white transition hover:shadow-lg rounded-md">
                                Pay with Card ({{ $page.props.cart.currency }} {{ $page.props.cart.total_price }} )
                                </button>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
        </section>
    </Layout>  
</template>
