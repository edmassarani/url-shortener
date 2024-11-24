<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Url } from '@/types/url';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const urlList = ref<Url[]>([]);

onMounted(() => {
    // repetition, would move to a pinia store and fetch the urls from that
    axios.get('/urls').then((res) => {
        urlList.value = res.data;
    });
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- style is a bit repetitive, could move to a customizable table component -->
                <div
                    v-if="urlList.length > 0"
                    class="grid w-full grid-cols-4 overflow-auto rounded-2xl border-transparent dark:text-white/50"
                >
                    <p class="bg-gray-200 px-6 py-4 dark:bg-gray-800">
                        Short Link
                    </p>
                    <p class="bg-gray-200 px-6 py-4 dark:bg-gray-800">
                        Original Link
                    </p>
                    <p class="bg-gray-200 px-6 py-4 dark:bg-gray-800">
                        Last Visit
                    </p>
                    <p class="bg-gray-200 px-6 py-4 dark:bg-gray-800">
                        Total Visits
                    </p>
                    <template v-for="url in urlList" :key="url.id">
                        <div
                            class="flex items-center gap-4 bg-gray-100 px-6 py-4 dark:bg-gray-950"
                        >
                            <p>https://linkly.com/{{ url.short_code }}</p>
                        </div>
                        <div class="bg-gray-100 px-6 py-4 dark:bg-gray-950">
                            <p>{{ url.long_url }}</p>
                        </div>
                        <div class="bg-gray-100 px-6 py-4 dark:bg-gray-950">
                            <p>{{ url.last_visit }}</p>
                        </div>
                        <div class="bg-gray-100 px-6 py-4 dark:bg-gray-950">
                            <p>{{ url.visit_count }}</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
