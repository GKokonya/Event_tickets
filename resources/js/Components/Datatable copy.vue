<script setup>
import { router,Link } from '@inertiajs/vue3';
import { ref  } from 'vue';
import Pagination from '@/Components/Pagination.vue';


let props=defineProps({
    columns:Array,
    resources: Array,
    actions:Array,
    route: String,
    button: String
});

let isCheckAll = false;

function checkAll(){

this.isCheckAll = !this.isCheckAll;

}

// declare a ref to hold the element reference
// the name must match template ref value
const id = ref({});



function submit() {
 // router.post(props.route, form)

  //input.value.focus()
  //console.log(id.value);
  router.post('/refunds/intiate', id.value)

}


function updateCheckall(){
    if(this.languages.length == this.langsdata.length){
        this.isCheckAll = true;
    }else{
        this.isCheckAll = false;
    }
}
</script>

<template>
    <form @submit.prevent="submit">
        <button type="submit" class="bg-gray-900 text-sm text-white rounded-lg p-2">{{button}}</button>
        <div class="flex flex-col mt-3">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow border-b border-gray-200 relative">

                        <table class="min-w-full divide-y divide-gray-200 bg-white">
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

                            <tbody class="bg-white divide-y divide-gray-200">
                            
                                <tr class="hover:bg-gray-50"  v-for="(key,index) in resources" :key="index">

                                    <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrapflex justify-end">
                                        <input 
                                        type="text" 
                                        :id="key.id"
                                        ref="id"
                                        :value="key.id"
                                        class="rounded-md border-gray-300 shadow-sm"
                                        />
                                    </td>

                                    <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrapflex justify-end" v-for="(value,index) in key" :key="index">{{value}}</td>
                                    <td class="text-sm py-4 px-6 text-gray-500 whitespace-nowrapflex justify-end" v-for="(action,index) in actions" :key="index">
                                        <Link class="bg-gray-900 text-sm text-white rounded-lg p-2" :href="action+key.id">
                                            {{ index }}
                                        </Link>
                                    </td>

                                </tr>                           
                            </tbody>

                        </table>
                    </div>
                <!--pagination-->
                <Pagination :links="resources.links" class="mt-2 flex justify-center"/>

                
                </div>
            </div>
        </div>
    </form>
</template>