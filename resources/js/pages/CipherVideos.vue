<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Barcha 12 ta shifr
const ciphers = [
    {
        name: 'Caesar Cipher',
        description: 'Harflarni alfavitda ma\'lum sondan siljitish asosida shifrlaydi.',
        color: 'from-indigo-400 to-purple-500',
        darkColor: 'dark:from-indigo-500 dark:to-purple-600',
        icon: 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12',
        videos: [
            { title: 'How to Use the Caesar (Shift) Cipher', url: 'https://www.youtube.com/watch?v=l6jqKRXSShI' },
            { title: 'Caesar Cipher (Part 1)', url: 'https://www.youtube.com/watch?v=JtbKh_12ctg' },
            { title: 'DIY Caesar Cypher!', url: 'https://youtu.be/eL9AmU5afR0?si=BWqXgWnZDeDgzhbX' },
        ],
    },
    {
        name: 'Monoalphabetic Cipher',
        description: 'Har bir harfni boshqa harf bilan almashtiruvchi oddiy usul.',
        color: 'from-pink-400 to-orange-400',
        darkColor: 'dark:from-pink-500 dark:to-orange-500',
        icon: 'M8 9l4-4 4 4m0 6l-4 4-4-4',
        videos: [
            { title: 'Monoalphabetic substitution Example', url: 'https://www.youtube.com/watch?v=PnVcFE93kUY' },
            { title: 'Break Monoalphabetic Substitution Cipher', url: 'https://www.youtube.com/watch?v=TRYuOjmXdxg' },
            { title: 'Monoalphabetic Cipher', url: 'https://www.youtube.com/watch?v=J-utjSeUq_c' },
        ],
    },
    {
        name: 'Playfair Cipher',
        description: '5×5 matritsa asosida juft harflar yordamida shifrlaydi.',
        color: 'from-cyan-300 to-blue-400',
        darkColor: 'dark:from-cyan-400 dark:to-blue-500',
        icon: 'M4 6h16M4 10h16M4 14h16M4 18h16',
        videos: [
            { title: 'Playfair Cipher (Part 1)', url: 'https://www.youtube.com/watch?v=UURjVI5cw4g' },
            { title: 'Playfair Cipher Explained', url: 'https://www.youtube.com/watch?v=quKhvu2tPy8' },
            { title: 'Playfair Cipher', url: 'https://www.youtube.com/watch?v=-KjFbTK1IIw' },
        ],
    },
    {
        name: 'Vigenère Cipher',
        description: 'Kalit so\'z orqali harflarni turlicha siljituvchi usul.',
        color: 'from-green-300 to-emerald-500',
        darkColor: 'dark:from-green-400 dark:to-emerald-600',
        icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
        videos: [
            { title: 'Vigenere Cipher', url: 'https://youtu.be/SkJcmCaHqS0?si=RArX5efa4BoLiRKQ' },
            { title: 'How to use the Vigenere Cipher', url: 'https://youtu.be/RCkGauRMs2A?si=K4MjhV2vlutzFfGC' },
            { title: 'Vigenere Cipher Explained (with Example)', url: 'https://youtu.be/J1o2zo6HleY?si=hV4VSbLonJeLxnFh' },
        ],
    },
    {
        name: 'Hill Cipher',
        description: 'Matritsali modulyar algebra asosida ishlovchi shifrlash.',
        color: 'from-yellow-300 to-amber-500',
        darkColor: 'dark:from-yellow-400 dark:to-amber-600',
        icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        videos: [
            { title: 'Hill Cipher (Encryption)', url: 'https://youtu.be/-EQ8UomTrAQ?si=fugm4pKG4veIynmQ' },
            { title: 'Hill Cipher Explained (with Example)', url: 'https://youtu.be/uRRYf2f7kYA?si=KX9xeoYOb1SBHKpM' },
            { title: 'Hill Cipher Encryption and Decryption', url: 'https://youtu.be/JK3ur6W4rvw?si=eC4C2-Rl_hDVv-Tx' },
        ],
    },
    {
        name: 'Beaufort Cipher',
        description: 'Vigenèrega o\'xshash, ammo teskari yonalishda ishlaydi.',
        color: 'from-red-300 to-rose-500',
        darkColor: 'dark:from-red-400 dark:to-rose-600',
        icon: 'M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4',
        videos: [
            { title: 'Cryptography: The Beaufort Cipher', url: 'https://youtu.be/cMj7M16PGmU?si=HmGWEhC8Lxf-NKxM' },
            { title: 'Beaufort Cipher Explained and Broken', url: 'https://youtu.be/1AAXVwxLGpE?si=_IrSUGtMelnGJg7Z' },
        ],
    },
    {
        name: 'Autokey Cipher',
        description: 'Kalit sifatida ochiq matndan bir qismi olinadigan usul.',
        color: 'from-sky-300 to-teal-400',
        darkColor: 'dark:from-sky-400 dark:to-teal-500',
        icon: 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z',
        videos: [
            { title: 'Autokey Cipher Explained', url: 'https://youtu.be/ywRRfc2t6w8?si=_h30sv_S2Fgf0xpm' },
            { title: 'Autokey Cipher Encryption', url: 'https://youtu.be/LdIqAtI-_CU?si=6Hg5O2ZPPgMbxKaR' },
        ],
    },
    {
        name: 'Homophonic Substitution',
        description: 'Bitta harf bir nechta belgilar bilan almashtiriladigan tur.',
        color: 'from-fuchsia-400 to-violet-500',
        darkColor: 'dark:from-fuchsia-500 dark:to-violet-600',
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        videos: [
            { title: 'Homophonic Substitution Cipher', url: 'https://youtu.be/KoTZ_Jy84ew?si=MJ5XiUkhwBRu62Xd' },
            { title: 'Cryptography: Homophonic Substitution', url: 'https://youtu.be/vNPfG0Ly6Go?si=gtrXENZPX25otVmx' },
        ],
    },
    {
        name: 'Affine Cipher',
        description: 'Harflar matematik formula asosida almashtiriladi: E(x) = (ax + b) mod m.',
        color: 'from-lime-300 to-green-500',
        darkColor: 'dark:from-lime-400 dark:to-green-600',
        icon: 'M12 4v16m8-8H4',
        videos: [
            { title: 'Affine Cipher Explained', url: 'https://youtu.be/iyESl17IqFQ?si=yGHfXaEW9oNRgpVm' },
            { title: 'Affine Cipher Encryption and Decryption', url: 'https://youtu.be/ZRtTfVdjUgk?si=SHtU0kEsZ8FyDNr3' },
        ],
    },
    {
        name: 'Atbash Cipher',
        description: 'Alfavitni teskari tartibda almashtirish orqali shifrlash.',
        color: 'from-purple-300 to-pink-400',
        darkColor: 'dark:from-purple-400 dark:to-pink-500',
        icon: 'M4 4h16v16H4V4z',
        videos: [
            { title: 'Atbash Cipher Explained', url: 'https://youtu.be/WYvHY7Kv3QU?si=2oYWD8rWjLF1i1Bh' },
            { title: 'Atbash Cipher Example', url: 'https://youtu.be/atU0V98NCKc?si=bxtBDFaOnA8ZtOro' },
        ],
    },
    {
        name: 'Scytale Cipher',
        description: 'Shpionlar asrlar oldingi usuli, rulo atrofida harflarni yozish asosida.',
        color: 'from-rose-300 to-red-400',
        darkColor: 'dark:from-rose-400 dark:to-red-500',
        icon: 'M12 2v20m-6-6h12',
        videos: [
            { title: 'Scytale Cipher Explained', url: 'https://youtu.be/-cooJOp7ah4?si=9TH6klDuNxbslS8G' },
            { title: 'Ancient Greek Scytale Cipher', url: 'https://youtu.be/uU9zmMuAkJE?si=o-YeAePfDkLsE6We' },
        ],
    },
    {
        name: 'Columnar Transposition Cipher',
        description: 'Matn harflari ustunlar bo‘yicha joyini o‘zgartirish orqali shifrlanadi.',
        color: 'from-teal-300 to-cyan-400',
        darkColor: 'dark:from-teal-400 dark:to-cyan-500',
        icon: 'M4 6h16M4 10h16M4 14h16M4 18h16',
        videos: [
            { title: 'Columnar Transposition Cipher', url: 'https://youtu.be/N2Lv2QcehTQ?si=NoZrCIlVr7hkE9gK' },
            { title: 'How Columnar Transposition Works', url: 'https://youtu.be/cPQXaYUMOjQ?si=1fwI5li6G6X6Zykq' },
        ],
    },
];

// Modal
const selectedCipher = ref<any | null>(null);
const searchQuery = ref('');

// Qidiruv
const filteredCiphers = computed(() => {
    if (!searchQuery.value) return ciphers;
    const query = searchQuery.value.toLowerCase();
    return ciphers.filter(c =>
        c.name.toLowerCase().includes(query) ||
        c.description.toLowerCase().includes(query)
    );
});

function openVideoModal(cipher: any) {
    selectedCipher.value = cipher;
}
</script>

<template>
    <Head title="Shifrlash Usullari" />

    <AppLayout :breadcrumbs="[{ title: 'Dashboard' }]">
        <div
            class="min-h-screen py-12 px-6 bg-gradient-to-br from-gray-50 via-white to-gray-100
             dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-950"
        >
            <!-- Header -->
            <div class="text-center mb-12 max-w-4xl mx-auto">
                <h1
                    class="text-5xl font-bold mb-4 tracking-tight
                 bg-gradient-to-r from-indigo-600 to-purple-600
                 dark:from-indigo-400 dark:to-purple-400
                 bg-clip-text text-transparent"
                >
                    Shifrlash Usullari
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium">
                    12 ta klassik shifr — video darsliklar bilan!
                </p>
            </div>

            <!-- Qidiruv -->
            <div class="max-w-2xl mx-auto mb-10">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Shifr nomi yoki tavsif..."
                    class="w-full px-6 py-4 rounded-2xl border-2 border-gray-300 dark:border-gray-700
                 bg-white dark:bg-gray-800 text-lg focus:outline-none focus:border-indigo-500
                 shadow-lg transition-all"
                />
            </div>

            <!-- Kartalar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
                <div
                    v-for="cipher in filteredCiphers"
                    :key="cipher.name"
                    @click="openVideoModal(cipher)"
                    class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl
                 transition-all duration-300 cursor-pointer border border-gray-200 dark:border-gray-700
                 hover:-translate-y-1"
                    :class="`hover:bg-gradient-to-br ${cipher.color} dark:hover:bg-gradient-to-br ${cipher.darkColor}`"
                >
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-4 p-3 bg-gray-100 dark:bg-gray-900 rounded-full transition-colors group-hover:bg-white/20">
                            <svg
                                class="w-10 h-10 text-gray-600 dark:text-gray-400 transition-colors"
                                :class="`group-hover:text-white`"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path :d="cipher.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-white transition-colors">
                            {{ cipher.name }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm group-hover:text-white/80 transition-colors">
                            {{ cipher.description }}
                        </p>
                        <div class="mt-3 flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 group-hover:text-white/70">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                            {{ cipher.videos.length }} video
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <transition name="fade">
                <div
                    v-if="selectedCipher"
                    class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
                    @click.self="selectedCipher = null"
                >
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-2xl max-h-[80vh] overflow-y-auto shadow-2xl"
                    >
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ selectedCipher.name }}
                            </h2>
                            <button
                                @click="selectedCipher = null"
                                class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="(video, i) in selectedCipher.videos"
                                :key="i"
                                class="p-4 bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20
                       rounded-lg border border-teal-200 dark:border-teal-800"
                            >
                                <a
                                    :href="video.url"
                                    target="_blank"
                                    class="flex items-center text-teal-700 dark:text-teal-300 hover:text-teal-900 dark:hover:text-teal-100 font-medium"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                                    </svg>
                                    {{ video.title }} (Yangi oynada ochish)
                                </a>
                            </div>

                            <p v-if="!selectedCipher.videos.length" class="text-center text-gray-500 py-8">
                                Hozircha video qo‘shilmagan
                            </p>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
