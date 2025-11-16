<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Bizning Jamoa' },
];
</script>

<template>
    <Head title="Bizning Jamoa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen py-16 px-6 bg-gradient-to-br from-gray-50 via-purple-50 to-indigo-50 dark:from-gray-950 dark:via-gray-900 dark:to-purple-950">
            <div class="max-w-7xl mx-auto">

                <!-- Header -->
                <div class="text-center mb-20">
                    <h1 class="text-7xl font-black bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent drop-shadow-lg">
                        Bizning Jamoa
                    </h1>
                    <p class="mt-6 text-2xl text-gray-700 dark:text-gray-300 font-medium">
                        CryptoClassic loyihasini yaratgan va rivojlantirayotgan mutaxassislar
                    </p>
                </div>

                <!-- Yaratuvchi — Mexriddin Nuriddinov (Eng yuqorida, alohida) -->
                <div class="max-w-5xl mx-auto mb-24">
                    <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-purple-200 dark:border-purple-800">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10"></div>
                        <div class="relative p-12 md:p-16 text-center">
                            <div class="mb-8">
                                <img
                                    src="/storage/team/mexriddin.jpg"
                                    alt="Mexriddin Nuriddinov"
                                    class="w-48 h-48 mx-auto rounded-full border-8 border-white dark:border-gray-800 shadow-2xl object-cover"
                                />
                            </div>
                            <h2 class="text-5xl font-black text-gray-900 dark:text-white mb-3">
                                Mexriddin Nuriddinov
                            </h2>
                            <div class="flex justify-center gap-8 text-lg font-semibold text-gray-700 dark:text-gray-300 mb-6">
                <span class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full shadow-lg">
                  Cybersecurity Specialist
                </span>
                                <span class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-full shadow-lg">
                  Software Engineer
                </span>
                            </div>
                            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed mb-8">
                                Loyiha asoschisi. Platformani yaratgan va loyihani boshqargan mutaxassis.
                            </p>
                            <div class="flex justify-center space-x-6">
                                <a href="https://github.com/MexriddinDev" target="_blank" class="group">
                                    <div class="p-4 bg-gray-100 dark:bg-gray-900 rounded-2xl group-hover:bg-indigo-600 transition-all duration-300">
                                        <svg class="w-8 h-8 text-gray-700 dark:text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                        </svg>
                                    </div>
                                </a>
                                <a href="https://www.linkedin.com/in/mexriddin-nuriddinov/" target="_blank" class="group">
                                    <div class="p-4 bg-gray-100 dark:bg-gray-900 rounded-2xl group-hover:bg-indigo-600 transition-all duration-300">
                                        <svg class="w-8 h-8 text-gray-700 dark:text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yordam berganlar -->
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-4xl font-bold text-center text-gray-800 dark:text-white mb-12">
                        Loyihada yordam berganlar
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                        <!-- 1 -->
                        <div class="group bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-3">
                            <div class="relative h-64 bg-gradient-to-br from-teal-400 to-cyan-600">
                                <img src="/storage/team/jamshid.jpg" alt="Xamidov Jamshid" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition-colors"></div>
                            </div>
                            <div class="p-8 text-center">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Xamidov Jamshid</h3>
                                <p class="text-teal-600 dark:text-teal-400 font-semibold mb-3">cybersecurity specialist</p>
                            </div>
                        </div>

                        <!-- 2 -->
                        <div class="group bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-3">
                            <div class="relative h-64 bg-gradient-to-br from-purple-400 to-pink-600">
                                <img src="/storage/team/samandar.jpg" alt="Najmiddinov Samandar" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition-colors"></div>
                            </div>
                            <div class="p-8 text-center">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Najmiddinov Samandar</h3>
                                <p class="text-purple-600 dark:text-purple-400 font-semibold mb-3">Cybersecurity specialist</p>
                            </div>
                        </div>

                        <!-- 3 -->
                        <div class="group bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-3">
                            <div class="relative h-64 bg-gradient-to-br from-amber-400 to-orange-600">
                                <img src="/storage/team/jaloliddin.jpg" alt="Qo'shmonov Jaloliddin" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition-colors"></div>
                            </div>
                            <div class="p-8 text-center">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Qo'shmonov Jaloliddin</h3>
                                <p class="text-amber-600 dark:text-amber-400 font-semibold mb-3">Cybersecurity specialist</p>
                            </div>
                        </div>

                        <!-- 4 -->
                        <div class="group bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-3">
                            <div class="relative h-64 bg-gradient-to-br from-emerald-400 to-teal-600">
                                <img src="/storage/team/nurbek.jpg" alt="Umarqulov Nurbek" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition-colors"></div>
                            </div>
                            <div class="p-8 text-center">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Umarqulov Nurbek</h3>
                                <p class="text-emerald-600 dark:text-emerald-400 font-semibold mb-3">Cybersecurity specialist</p>
                            </div>
                        </div>

                        <!-- 5 -->
                        <div class="group bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-3">
                            <div class="relative h-64 bg-gradient-to-br from-rose-400 to-pink-600">
                                <img src="/storage/team/firdavs.jpg" alt="Mirxonov Firdavs" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition-colors"></div>
                            </div>
                            <div class="p-8 text-center">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Mirxonov Firdavs</h3>
                                <p class="text-rose-600 dark:text-rose-400 font-semibold mb-3">Cybersecurity specialist</p>
                            </div>
                        </div>

                        <!-- 6 -->
                        <div class="group bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-3">
                            <div class="relative h-64 bg-gradient-to-br from-indigo-400 to-blue-600">
                                <img src="/storage/team/javohir.jpg" alt="Izomiddinov Javohir" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition-colors"></div>
                            </div>
                            <div class="p-8 text-center">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Izomiddinov Javohir</h3>
                                <p class="text-indigo-600 dark:text-indigo-400 font-semibold mb-3">Cybersecurity specialist</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-20">
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        <span class="font-bold text-2xl text-indigo-600 dark:text-indigo-400">CryptoClassic</span> — O‘zbekistonda birinchi va yagona klassik kriptografiya platformasi
                    </p>
                    <p class="mt-4 text-gray-500 dark:text-gray-500">
                        © 2025 Mexriddin Nuriddinov va jamoasi. Barcha huquqlar himoyalangan.
                    </p>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.hover\:scale-110 {
    transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
