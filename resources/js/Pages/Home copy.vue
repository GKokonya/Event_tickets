<script setup>
import { Head } from '@inertiajs/vue3';
defineProps({events:Array});
</script>

<style>
body{

}
</style>
<template>
    <Head title="Home"></Head>
    <div  class="tex-gray-600"> <!-- content wrapper-->
        <div>
            <nav>
                <div>
                    <h1>
                        <a href="/">Food Ninja</a>
                    </h1>
                </div>
                <ul>
                    <li class="text-red-700">
                        <a href="#">
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span>About</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span>Contact</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div><!-- End nav -->

        <main>
            <div>
                <a href="#">Log in</a>
                <a href="#">Sign up</a>
            </div>

            <header>
                <h2>Recipes</h2>
            </header>

            <div>
                <!-- cards go here -->
                <div v-for="event in events.data" :key="event.id">
                    <img :src="event.image" alt="" />
                    <div>
                        <span>5 bean Chilli Stew</span>
                        <span>Recepe by Mario</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>