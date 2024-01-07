<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { reactive,ref } from 'vue';
import { Head,useForm,router } from '@inertiajs/vue3';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

defineProps({events:Array});



const form = useForm({
  title: null,
  venue: null,
  image: null,
  start_date: null,
  end_date: null,
  start_time: null,
  end_time: null,

})


function submit() {
  form.post('/events')
}



</script>

<template>
  <Head title="Events" />
  <AdminLayout>
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mx-auto mt-4 max-w-2xl md:mt-12">
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-6 sm:px-8 sm:py-10">
            <div class="flow-root">
              <form @submit.prevent="submit">
                <div>
                  <label for="Event" class="block text-xs font-medium text-gray-700"> Event </label>
                  <input type="text"  v-model="form.title" placeholder="e.g Koroga Fest" class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"/>
                </div>
                <div>
                  <label for="Venue" class="block text-xs font-medium text-gray-700"> Venue </label>
                  <input type="text"  v-model="form.venue" placeholder="Ngong Race Course, Nairobi" class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"/>
                </div>
                <div>
                  <label for="Image" class="block text-xs font-medium text-gray-700"> Image </label>
                  <input type="file" @input="form.image = $event.target.files[0]" class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"/>
                </div>
                <div>
                  <label for="Start Date" class="block text-xs font-medium text-gray-700"> Start Date </label>
                  <input type="date"  v-model="form.start_date" class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"/>
                </div>
                <div>
                  <label for="End Date" class="block text-xs font-medium text-gray-700"> End Date </label>
                  <input type="date"  v-model="form.end_date" class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"/>
                </div>
                <div>
                  <label for="Start Time" class="block text-xs font-medium text-gray-700"> Start Time </label>
                  <input type="time"  v-model="form.start_time" class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"/>
                </div>
                <div>
                  <label for="End Time" class="block text-xs font-medium text-gray-700"> End Time </label>
                  <input type="time"  v-model="form.end_time" class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"/>
                </div>

                <div>
                  <button type="submit" class="group inline-flex w-full items-center justify-center rounded-md bg-gray-900 px-6 py-4 text-lg font-semibold text-white transition-all duration-200 ease-in-out focus:shadow hover:bg-gray-800">Submit</button>
                </div>

              </form>


              <div>


            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>