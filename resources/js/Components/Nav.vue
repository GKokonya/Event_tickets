<template>
    <nav class="w-full py-1 h-12 shadow font-bold">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-between">
            <nav>
                <ul class="flex items-center justify-between font-bold text-sm uppercase no-underline">
                    <NavLink href="/" class="text-gray-800 px-4 no-underline text-2xl"  :active="$page.component==='Home'">
                    <!-- <img :src="$page.props.images.logo" class="w-16 h-12" /> -->
                    TIKO SAFI
                    </NavLink>
                </ul>
            </nav>

            <div class="flex items-center text-lg no-underline pl-6">
                
                <NavLink :href="route('cart')" class="flex mr-4 text-2xl"> 
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>

                    <span class="mb-4 bg-white text-center text-sm rounded-full w-5 h-5">
                        {{$page.props.cart.item_count}}
                    </span>
                </NavLink>
            </div>
        </div>
    </nav>
</template>

<script setup>
import NavLink from './NavLink.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    cart:  Array
});
</script>