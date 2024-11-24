<script setup lang="ts">
import LinkIcon from '@/Components/LinkIcon.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();

const longUrl = ref('');
const urlList = ref([]);

const submitUrl = () => {
    axios.post('/urls/shorten', { long_url: longUrl.value }).then(() => {
        longUrl.value = '';
        getUrls();
    });
};

const getUrls = () => {
    axios.get('/urls').then((res) => {
        urlList.value = res.data;
    });
};

onMounted(() => {
    getUrls();
});
</script>

<template>
    <Head title="Home" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="selection:bg-laravel-red relative flex min-h-screen flex-col items-center selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="flex items-center py-10">
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="focus-visible:ring-laravel-red rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="focus-visible:ring-laravel-red rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="bg-laravel-red rounded-md px-3 py-2 text-white transition hover:bg-opacity-80"
                            >
                                Register Now
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-20">
                    <div class="flex flex-col items-center gap-12">
                        <h1
                            class="text-center text-6xl font-extrabold text-black dark:text-white"
                        >
                            Shorten Your Loooong Links :)
                        </h1>

                        <p class="max-w-xl text-center">
                            Linkly is an efficient and easy-to-use URL
                            shortening service that streamlines your online
                            experience.
                        </p>

                        <label
                            for="long-url"
                            class="flex min-w-[50%] items-center gap-2 rounded-full border py-1 pl-3 pr-1 dark:bg-gray-900"
                        >
                            <LinkIcon
                                class="size-5 fill-black dark:fill-white"
                            />
                            <input
                                id="long-url"
                                v-model="longUrl"
                                type="text"
                                placeholder="Enter the link here"
                                class="grow border-0 border-transparent bg-transparent text-black outline-none focus:ring-0 dark:text-white"
                                @keydown.enter.prevent="submitUrl"
                            />
                            <button
                                class="rounded-full bg-gray-200 p-4 font-bold dark:bg-gray-400 dark:text-gray-900"
                                @click="submitUrl"
                            >
                                Shorten Now!
                            </button>
                        </label>

                        <div>
                            {{ urlList }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
