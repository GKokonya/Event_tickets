<template>

    <footer aria-label="Site Footer" class="bg-yellow-300 font-bold">
        <div class="relative mx-auto max-w-screen-xl px-4 py-2 sm:px-6 lg:px-8 lg:pt-24">
            <div class="lg:flex lg:items-end lg:justify-between">
                <div class="flex justify-center text-teal-600 lg:justify-start">
                    <img :src="$page.props.images.logo" class="h-24"/>
                </div>
                <div>
                    <p class="mx-auto mt-6 max-w-md text-center leading-relaxed lg:text-left">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Incidunt
                    consequuntur amet culpa cum itaque neque.
                    </p>
                </div>

                <nav aria-label="Footer Nav" class="mt-2 lg:mt-0">
                    <ul class="flex flex-wrap justify-center gap-6 md:gap-8 lg:justify-end lg:gap-12">
                        <li>
                            <a class="text-gray-700 transition hover:text-gray-700/75" href="/">About</a>
                        </li>

                        <li>
                            <a class="text-gray-700 transition hover:text-gray-700/75" href="/">Services</a>
                        </li>

                        <li>
                            <a class="text-gray-700 transition hover:text-gray-700/75" href="/">Projects</a>
                        </li>

                        <li>
                            <a class="text-gray-700 transition hover:text-gray-700/75" href="/">Blog</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <p class="mt-4 text-center text-sm">
            Copyright &copy; 2022. All rights reserved.
            </p>
        </div>
    </footer>
</template>

<script setup>

</script>
