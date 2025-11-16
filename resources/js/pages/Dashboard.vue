<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface CipherInfo {
    name: string;
    description: string;
    color: string;
    darkColor: string;
    icon: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];

const searchQuery = ref('');

const ciphers: CipherInfo[] = [

    {
        name: 'Caesar Cipher',
        description: 'Harflarni alfavitda malum sondan siljitish asosida shifrlaydi.',
        color: 'from-indigo-400 to-purple-500',
        darkColor: 'dark:from-indigo-500 dark:to-purple-600',
        icon: 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'
    },
    {
        name: 'Monoalphabetic Cipher',
        description: 'Har bir harfni boshqa harf bilan almashtiruvchi oddiy usul.',
        color: 'from-pink-400 to-orange-400',
        darkColor: 'dark:from-pink-500 dark:to-orange-500',
        icon: 'M8 9l4-4 4 4m0 6l-4 4-4-4'
    },
    {
        name: 'Playfair Cipher',
        description: '5×5 matritsa asosida juft harflar yordamida shifrlaydi.',
        color: 'from-cyan-300 to-blue-400',
        darkColor: 'dark:from-cyan-400 dark:to-blue-500',
        icon: 'M4 6h16M4 10h16M4 14h16M4 18h16'
    },
    {
        name: 'Vigenère Cipher',
        description: 'Kalit soz orqali harflarni turlicha siljituvchi usul.',
        color: 'from-green-300 to-emerald-500',
        darkColor: 'dark:from-green-400 dark:to-emerald-600',
        icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'
    },
    {
        name: 'Hill Cipher',
        description: 'Matritsali modulyar algebra asosida ishlovchi shifrlash.',
        color: 'from-yellow-300 to-amber-500',
        darkColor: 'dark:from-yellow-400 dark:to-amber-600',
        icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'
    },
    {
        name: 'Beaufort Cipher',
        description: 'Vigenèrega oxshash, ammo teskari yonalishda ishlaydi.',
        color: 'from-red-300 to-rose-500',
        darkColor: 'dark:from-red-400 dark:to-rose-600',
        icon: 'M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4'
    },
    {
        name: 'Autokey Cipher',
        description: 'Kalit sifatida ochiq matndan bir qismi olinadigan usul.',
        color: 'from-sky-300 to-teal-400',
        darkColor: 'dark:from-sky-400 dark:to-teal-500',
        icon: 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z'
    },
    {
        name: 'Homophonic Substitution',
        description: 'Bitta harf bir nechta belgilar bilan almashtiriladigan tur.',
        color: 'from-fuchsia-400 to-violet-500',
        darkColor: 'dark:from-fuchsia-500 dark:to-violet-600',
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
    },
    {
        name: 'Affine Cipher',
        description: 'Harflar matematik formula asosida almashtiriladi: E(x) = (ax + b) mod m.',
        color: 'from-lime-300 to-green-500',
        darkColor: 'dark:from-lime-400 dark:to-green-600',
        icon: 'M12 4v16m8-8H4'
    },
    {
        name: 'Atbash Cipher',
        description: 'Alfavitni teskari tartibda almashtirish orqali shifrlash.',
        color: 'from-purple-300 to-pink-400',
        darkColor: 'dark:from-purple-400 dark:to-pink-500',
        icon: 'M4 4h16v16H4V4z'
    },
    {
        name: 'Scytale Cipher',
        description: 'Shpionlar asrlar oldingi usuli, rulo atrofida harflarni yozish asosida.',
        color: 'from-rose-300 to-red-400',
        darkColor: 'dark:from-rose-400 dark:to-red-500',
        icon: 'M12 2v20m-6-6h12'
    },
    {
        name: 'Columnar Transposition Cipher',
        description: 'Matn harflari ustunlar bo‘yicha joyini o‘zgartirish orqali shifrlanadi.',
        color: 'from-teal-300 to-cyan-400',
        darkColor: 'dark:from-teal-400 dark:to-cyan-500',
        icon: 'M4 6h16M4 10h16M4 14h16M4 18h16'
    },


];

const filteredCiphers = computed(() => {
    if (!searchQuery.value) return ciphers;
    return ciphers.filter(cipher =>
        cipher.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        cipher.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>

<template>
    <Head title="CryptoClassic Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen flex flex-col items-center justify-start py-12 px-6
                bg-gradient-to-br from-gray-50 via-white to-gray-100
                dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-950
                rounded-2xl shadow-xl transition-colors duration-300">

            <!-- Header Section -->
            <div class="text-center mb-12 max-w-3xl">
                <div class="mb-4 inline-block">
                    <svg class="w-16 h-16 mx-auto text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4 tracking-tight
                       bg-gradient-to-r from-indigo-600 to-purple-600
                       dark:from-indigo-400 dark:to-purple-400
                       bg-clip-text text-transparent">
                    CryptoClassic
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Klassik Shifrlash Usullari
                </p>
                <p class="text-gray-500 dark:text-gray-500 leading-relaxed">
                    Quyida eng mashhur klassik shifrlash usullarining qisqacha tavsiflari bilan tanishing.
                </p>
            </div>

            <!-- Search Bar -->
            <div class="w-full max-w-2xl mb-10">
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Shifrlash usulini qidirish..."
                        class="w-full px-6 py-4 pl-12 rounded-2xl
                           bg-white dark:bg-gray-800
                           border-2 border-gray-200 dark:border-gray-700
                           text-gray-900 dark:text-white
                           placeholder-gray-400 dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400
                           focus:border-transparent
                           transition-all duration-300
                           shadow-sm hover:shadow-md"
                    />
                    <svg
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Cipher Cards Grid -->
            <TransitionGroup
                name="fade"
                tag="div"
                class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full max-w-7xl"
            >
                <div
                    v-for="(cipher, index) in filteredCiphers"
                    :key="cipher.name"
                    class="rounded-2xl p-[2px] bg-gradient-to-br transition-all duration-300
                       hover:scale-105 hover:shadow-2xl cursor-pointer group"
                    :class="[cipher.color, cipher.darkColor]"
                    :style="{ transitionDelay: `${index * 50}ms` }"
                >
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 h-full flex flex-col
                            transition-all duration-300
                            group-hover:bg-gray-50 dark:group-hover:bg-gray-850">

                        <!-- Cipher Icon -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br flex items-center justify-center
                                    shadow-lg transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3"
                                 :class="[cipher.color, cipher.darkColor]">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="cipher.icon" />
                                </svg>
                                <!-- Decorative glow effect -->
                                <div class="absolute inset-0 rounded-2xl bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <svg
                                    class="w-5 h-5 text-gray-400 dark:text-gray-600
                                       group-hover:text-gray-600 dark:group-hover:text-gray-400
                                       transform group-hover:translate-x-1 transition-all duration-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-grow">
                            <h2 class="text-xl font-bold mb-3
                                   text-gray-900 dark:text-white
                                   group-hover:text-indigo-600 dark:group-hover:text-indigo-400
                                   transition-colors duration-300">
                                {{ cipher.name }}
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                {{ cipher.description }}
                            </p>
                        </div>

                        <!-- Bottom Badge -->
                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                     bg-gradient-to-r text-white shadow-sm"
                                  :class="[cipher.color, cipher.darkColor]">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                Klassik Shifr
                            </span>
                        </div>
                    </div>
                </div>
            </TransitionGroup>

            <!-- Empty State -->
            <div v-if="filteredCiphers.length === 0" class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-500 dark:text-gray-500 text-lg">
                    Hech qanday natija topilmadi
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active {
    transition: all 0.5s ease;
}
.fade-enter-from {
    opacity: 0;
    transform: translateY(20px);
}
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-leave-to {
    opacity: 0;
    transform: scale(0.9);
}
</style>
