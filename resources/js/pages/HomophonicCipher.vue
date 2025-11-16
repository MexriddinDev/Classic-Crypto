<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Homophonic Substitution' },
];

const plaintext = ref('');
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');

// Homophonic mapping: Har bir harf bir nechta shifrlarga ega
// A-Z → 00-99 oralig'idagi raqamlar, eng ko'p ishlatiladigan harflar ko'proq kodga ega
const homophonicMap: Record<string, string[]> = {
    'E': ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11'], // 12 ta
    'T': ['12', '13', '14', '15', '16', '17', '18', '19', '20', '21'], // 10 ta
    'A': ['22', '23', '24', '25', '26', '27', '28', '29', '30'], // 9 ta
    'O': ['31', '32', '33', '34', '35', '36', '37', '38'], // 8 ta
    'I': ['39', '40', '41', '42', '43', '44', '45'], // 7 ta
    'N': ['46', '47', '48', '49', '50', '51'], // 6 ta
    'S': ['52', '53', '54', '55', '56'], // 5 ta
    'H': ['57', '58', '59', '60'], // 4 ta
    'R': ['61', '62', '63'], // 3 ta
    'D': ['64', '65', '66'], // 3 ta
    'L': ['67', '68', '69'], // 3 ta
    'C': ['70', '71'], // 2 ta
    'U': ['72', '73'], // 2 ta
    'M': ['74', '75'], // 2 ta
    'W': ['76', '77'], // 2 ta
    'F': ['78', '79'], // 2 ta
    'G': ['80', '81'], // 2 ta
    'Y': ['82', '83'], // 2 ta
    'P': ['84', '85'], // 2 ta
    'B': ['86', '87'], // 2 ta
    'V': ['88'], // 1 ta
    'K': ['89'], // 1 ta
    'J': ['90'], // 1 ta
    'X': ['91'], // 1 ta
    'Q': ['92'], // 1 ta
    'Z': ['93'], // 1 ta
};

// Teskari xarita: raqam → harf (faqat bitta variant)
const reverseMap: Record<string, string> = {};
Object.entries(homophonicMap).forEach(([letter, codes]) => {
    codes.forEach(code => {
        reverseMap[code] = letter;
    });
});

function validateInputs(): boolean {
    if (!plaintext.value.trim()) {
        errorMessage.value = 'Matnni kiriting!';
        return false;
    }
    if (!/^[A-Za-z\s]+$/.test(plaintext.value)) {
        errorMessage.value = 'Matn faqat harflardan iborat bo\'lishi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

// Harf chastotasiga qarab tasodifiy kod tanlash
function getRandomCode(letter: string): string {
    const codes = homophonicMap[letter];
    if (!codes) return letter; // Agar topilmasa, o'zini qaytar
    const randomIndex = Math.floor(Math.random() * codes.length);
    return codes[randomIndex];
}

function encrypt() {
    if (!validateInputs()) return;

    const upperPlain = plaintext.value.toUpperCase().replace(/[^A-Z]/g, '');
    let result = '';

    for (const char of upperPlain) {
        result += getRandomCode(char) + ' ';
    }

    ciphertext.value = result.trim();
}

function decrypt() {
    if (!validateInputs()) return;

    const codes = plaintext.value.trim().split(/\s+/);
    let result = '';

    for (const code of codes) {
        const letter = reverseMap[code];
        if (letter) {
            result += letter;
        } else {
            result += '?'; // Noma'lum kod
        }
    }

    ciphertext.value = result;
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}

// Harflar chastotasini hisoblash
const frequencyData = computed(() => {
    const text = plaintext.value.toUpperCase().replace(/[^A-Z]/g, '');
    const freq: Record<string, number> = {};
    let total = 0;

    for (const char of text) {
        freq[char] = (freq[char] || 0) + 1;
        total++;
    }

    return Object.entries(freq)
        .map(([letter, count]) => ({
            letter,
            count,
            percent: total > 0 ? (count / total * 100).toFixed(1) : '0',
            codes: homophonicMap[letter]?.length || 0
        }))
        .sort((a, b) => b.count - a.count);
});
</script>

<template>
    <Head title="Homophonic Substitution Cipher" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen flex flex-col items-center justify-start py-12 px-6
                bg-gradient-to-br from-gray-50 via-white to-gray-100
                dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-950
                rounded-2xl shadow-xl transition-colors duration-300">

            <!-- Header Section -->
            <div class="text-center mb-12 max-w-3xl">
                <div class="mb-4 inline-block">
                    <svg class="w-16 h-16 mx-auto text-fuchsia-600 dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4 tracking-tight
                       bg-gradient-to-r from-fuchsia-600 to-violet-600
                       dark:from-fuchsia-400 dark:to-violet-400
                       bg-clip-text text-transparent">
                    Homophonic Substitution
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Bitta harf — bir nechta belgi
                </p>
            </div>

            <!-- Encryption/Decryption Form -->
            <div class="w-full max-w-2xl mb-10 bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg">
                    {{ errorMessage }}
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">Matn (Plaintext yoki Ciphertext)</label>
                        <textarea
                            v-model="plaintext"
                            placeholder="Shifrlanadigan yoki deshifrlanadigan matnni kiriting..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                               focus:outline-none focus:border-fuchsia-500 dark:focus:border-fuchsia-400
                               transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Faqat A-Z harflari. Bo'shliqlar olib tashlanadi.</p>
                    </div>

                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                               bg-gradient-to-r from-fuchsia-500 to-violet-600
                               hover:from-fuchsia-600 hover:to-violet-700
                               transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Encrypt)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                               bg-gradient-to-r from-purple-500 to-pink-600
                               hover:from-purple-600 hover:to-pink-700
                               transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Deshifrlash (Decrypt)
                        </button>
                    </div>

                    <div v-if="ciphertext">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">Natija</label>
                        <textarea
                            v-model="ciphertext"
                            readonly
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                               bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-mono text-sm
                               cursor-not-allowed"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <!-- Frequency Table (only for plaintext) -->
                <div v-if="frequencyData.length > 0 && plaintext" class="mt-6 p-4 bg-gradient-to-br from-fuchsia-50 to-violet-50 dark:from-fuchsia-900/20 dark:to-violet-900/20 rounded-xl border border-fuchsia-200 dark:border-fuchsia-800">
                    <h3 class="text-sm font-semibold text-fuchsia-700 dark:text-fuchsia-300 mb-3">Harflar chastotasi</h3>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 text-xs">
                        <div v-for="item in frequencyData" :key="item.letter"
                             class="bg-white dark:bg-gray-800 p-2 rounded-lg text-center border border-fuchsia-100 dark:border-fuchsia-700">
                            <div class="font-bold text-fuchsia-600 dark:text-fuchsia-400">{{ item.letter }}</div>
                            <div class="text-gray-600 dark:text-gray-400">{{ item.percent }}%</div>
                            <div class="text-xs text-gray-500">({{ item.codes }} kod)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Button -->
            <button
                @click="toggleAbout"
                class="px-8 py-4 rounded-2xl font-medium text-white
                   bg-gradient-to-r from-indigo-500 to-purple-600
                   hover:from-indigo-600 hover:to-purple-700
                   transition-all duration-300 shadow-md hover:shadow-lg mb-6"
            >
                About Homophonic Substitution
            </button>

            <!-- About Section (Collapsible) -->
            <transition name="fade">
                <div v-if="showAbout" class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Homophonic Substitution haqida batafsil</h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Homophonic Substitution</strong> — bu oddiy almashtirish shifrining rivojlangan shakli bo'lib, <strong>bitta harf bir nechta turli belgilarga almashtiriladi</strong>. Bu chastota tahlilini qiyinlashtirish uchun ishlatiladi.
                    </p>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <h3 class="font-semibold text-red-700 dark:text-red-300 mb-2">Oddiy Almashtirish</h3>
                            <p class="text-sm"><code>E → X</code> (har doim X)</p>
                            <p class="text-xs mt-1">Chastota tahlili oson</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <h3 class="font-semibold text-green-700 dark:text-green-300 mb-2">Homophonic</h3>
                            <p class="text-sm"><code>E → 00, 01, 02, ..., 11</code> (12 ta variant)</p>
                            <p class="text-xs mt-1">Chastota tahlili qiyin</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">Qanday ishlaydi?</h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                        <li><strong>Chastotaga qarab kodlar taqsimlanadi</strong>: E (12 ta), T (10 ta), A (9 ta)...</li>
                        <li><strong>Har bir harf uchun tasodifiy kod tanlanadi</strong>.</li>
                        <li><strong>Shifrlashda har bir harf boshqa kodga ega bo'lishi mumkin</strong>.</li>
                        <li><strong>Deshifrlashda kod → harf (faqat bitta yo'l)</strong>.</li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6">
                        <p><strong>Misol:</strong> Matn: <code>THE</code></p>
                        <p class="mt-2">
                            T → 15 (yoki 12, 18, ...)<br>
                            H → 58 (yoki 57, 60, ...)<br>
                            E → 05 (yoki 00, 03, 11, ...)
                        </p>
                        <p class="mt-2 font-bold text-green-600 dark:text-green-400">Natija: <code>15 58 05</code></p>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700 mb-6">
                        <h4 class="font-semibold text-amber-800 dark:text-amber-200 mb-2">Afzalliklari</h4>
                        <ul class="text-sm text-amber-700 dark:text-amber-300 space-y-1">
                            <li>Chastota tahliliga chidamli</li>
                            <li>Oddiy shifrlardan xavfsizroq</li>
                            <li>Kalitni saqlash oson</li>
                        </ul>
                    </div>

                    <div class="bg-red-50 dark:bg-red-900/30 p-5 rounded-xl border border-red-300 dark:border-red-700">
                        <h4 class="font-semibold text-red-800 dark:text-red-200 mb-2">Kamchiliklari</h4>
                        <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                            <li>Deshifrlash uchun jadval kerak</li>
                            <li>Uzoq matnlar uchun kodlar takrorlanadi</li>
                            <li>Zamonaviy kompyuterlar oldida zaif</li>
                        </ul>
                    </div>

                    <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                        <div class="bg-fuchsia-50 dark:bg-fuchsia-900/20 p-4 rounded-lg">
                            <p class="text-xs text-fuchsia-700 dark:text-fuchsia-300">16-asr</p>
                            <p class="font-semibold">Paydo bo'lgan</p>
                        </div>
                        <div class="bg-violet-50 dark:bg-violet-900/20 p-4 rounded-lg">
                            <p class="text-xs text-violet-700 dark:text-violet-300">94 ta kod</p>
                            <p class="font-semibold">Umumiy</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <p class="text-xs text-purple-700 dark:text-purple-300">Chastota</p>
                            <p class="font-semibold">Asos</p>
                        </div>
                        <div class="bg-pink-50 dark:bg-pink-900/20 p-4 rounded-lg">
                            <p class="text-xs text-pink-700 dark:text-pink-300">Zamonaviy</p>
                            <p class="font-semibold">Zaif</p>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
