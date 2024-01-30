<script setup>
import { useForm } from '@inertiajs/vue3';
import Loader from '@/Components/Loader.vue';

import Layout from '@/Components/Layout.vue';
let props=defineProps({ checkoutRequestID:String});




let myInterval=setInterval(function() { 
    const form =  useForm({checkoutRequestID:props.checkoutRequestID});
    fetchlnmo(form,myInterval)
}, 3000);


function fetchlnmo(form,stopInterval){
    axios.post('/checkout/mpesa/stk/confirm-payment', form)
    .then((response) => {
        if(response.data.resultCode=='0'){
            clearInterval(stopInterval);
            form.get(route('checkout.mpesa.stk.success'));
        }

        if(response.data.resultCode!='0' && response.data.resultCode!=null){
            clearInterval(stopInterval);
            form.get(route('checkout.mpesa.stk.failure'));
        }


    })
    .catch((error) => {
    console.log(error);
    });
}

</script>

<template>
    <Layout :title="title" :header="title">
        <!-- EventList Section -->
        <section>
            <div class="rounded shadow-lg bg-gray-300 px-4 py-4 my-4">
                <h1 class="font-bold text-2xl">Your payment is being processed</h1>
                <h1 class="font-medium text-md">If you have made M-PESA payment, please check your phone and complete the transaction</h1>
                <Loader/>
            </div>
        </section>
    </Layout>  
</template>
