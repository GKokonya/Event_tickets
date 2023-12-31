<script setup>
import { ref } from 'vue'
defineProps({title : String, message: String, isSuccess: Boolean});

let open = ref(true);


function close(){
    open.value=false;
}
console.log(open.value);
</script>

<template>
    <div  v-if="open=open" role="alert"  class="rounded-xl border border-gray-100 p-4 shadow-xl t">
        <div class="flex items-start gap-4">
            <div class="flex-1">
                <strong class="block font-bold" :class="isSuccess==true? 'text-green-600' : 'text-red-900'"> {{ title }} </strong>
                <p class="mt-1 text-sm text-gray-700">{{ message }}</p>
            </div>

            <button  v-on:click="close" class="text-gray-500 transition hover:text-gray-600">
          
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>

            </button>
        </div>
        <div>
            <slot />
        </div>
    </div>
</template>