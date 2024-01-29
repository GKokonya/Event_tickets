<template>
    <div class="px-4 py-4 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8 lg:py-20">
        <div class="grid gap-8 lg:grid-cols-3 sm:max-w-sm sm:mx-auto lg:max-w-full">

            <div class="overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-sm" v-for="event in events.data" :key="event.id">

                <div>
                    <div class="card">
                        <Link :href="event.url" aria-label="">
                            <img :src="event.image" class="object-cover w-full h-64" :alt="event.title" />
                        </Link>
                        <div class="button-grp">
                        <button class="likes">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>
                        <button class="download">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15M9 12l3 3m0 0l3-3m-3 3V2.25" />
                            </svg>
                        </button>
                        </div>
                    </div>
                </div>




            </div>
         </div>

             <!--pagination-->
    <Pagination :links="events.links" :first_page="events.first_page_url" class="mt-2 flex justify-center"/>
    </div>

</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Pagination from '../Components/Pagination.vue';
defineProps({
   events : Array,
});

</script>


<style>
.card {
  position: relative;
  width: 300px;
  height: auto;
}

img {
  width: inherit;
  border-radius: 10px;
}

.download{
    position:absolute;
    bottom:8%;
    right:7%;
    padding:10px;
    background-color: rgb(255, 255, 255);
    border-radius: 10%;
    display: none;
}

.likes{
    position:absolute;
    bottom:8%;
    left:7%;
    padding:10px;
    background-color: rgb(255, 255, 255);
    border-radius: 10%;
    display: none;
}
button:hover {
  background-color: rgb(206, 206, 206);
  color: black;
}

.card:hover button {
  display: block;
}

</style>