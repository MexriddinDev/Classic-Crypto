<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, scytale } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Scytale Cipher' },
];

const plaintext = ref('');
const diameter = ref(5); // Rulo diametri (ustunlar soni)
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');
const showGrid = ref(false);

// To‘ldirish belgisi
const paddingChar = 'X';

// To‘ldirish funksiyasi
function padText(text: string, cols: number): string {
    const clean = text.toUpperCase().replace(/[^A-Z]/g, '');
    const remainder = clean.length % cols;
    if (remainder === 0) return clean;
    const needed = cols - remainder;
    return clean + paddingChar.repeat(needed);
}

// To‘ldirishni olib tashlash
function unpadText(text: string): string {
    return text.replace(new RegExp(`${paddingChar}+$`), '');
}

function validateInputs(): boolean {
    if (!plaintext.value.trim()) {
        errorMessage.value = 'Matnni kiriting!';
        return false;
    }
    if (!/^[A-Za-z\s]+$/.test(plaintext.value)) {
        errorMessage.value = 'Matn faqat A-Z harflardan iborat bo\'lishi kerak!';
        return false;
    }
    if (diameter.value < 2 || diameter.value > 20) {
        errorMessage.value = 'Rulo diametri 2 dan 20 gacha bo\'lishi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

// Scytale shifrlash (to‘ldirish bilan)
function scytaleEncrypt(text: string, cols: number): string {
    const padded = padText(text, cols);
    const rows = Math.ceil(padded.length / cols);
    let result = '';

    for (let c = 0; c < cols; c++) {
        for (let r = 0; r < rows; r++) {
            const index = r * cols + c;
            if (index < padded.length) {
                result += padded[index];
            }
        }
    }
    return result;
}

// Scytale deshifrlash (to‘ldirishni olib tashlash bilan)
function scytaleDecrypt(text: string, cols: number): string {
    const cleanText = text.toUpperCase().replace(/[^A-Z]/g, '');
    const rows = Math.ceil(cleanText.length / cols);
    let result = '';

    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            const index = c * rows + r;
            if (index < cleanText.length) {
                result += cleanText[index];
            }
        }
    }
    return unpadText(result);
}

// Grid: vizual jadval (to‘ldirish bilan)
const grid = computed(() => {
    if (!plaintext.value || !diameter.value) return [];
    const padded = padText(plaintext.value, diameter.value);
    const cols = diameter.value;
    const rows = Math.ceil(padded.length / cols);
    const result: string[][] = Array(rows).fill(null).map(() => Array(cols).fill(''));

    let index = 0;
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            result[r][c] = padded[index++] || '';
        }
    }
    return result;
});

function encrypt() {
    if (!validateInputs()) return;
    showGrid.value = true;
    ciphertext.value = scytaleEncrypt(plaintext.value, diameter.value);
}

function decrypt() {
    if (!validateInputs()) return;
    showGrid.value = true;
    ciphertext.value = scytaleDecrypt(plaintext.value, diameter.value);
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}
</script>

<template>
    <Head title="Scytale Cipher" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="min-h-screen flex flex-col items-center justify-start py-12 px-6
             bg-gradient-to-br from-gray-50 via-white to-gray-100
             dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-950
             rounded-2xl shadow-xl transition-colors duration-300"
        >
            <!-- Header -->
            <div class="text-center mb-12 max-w-3xl">
                <div class="mb-4 inline-block">
                    <svg
                        class="w-16 h-16 mx-auto text-red-600 dark:text-red-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                        />
                    </svg>
                </div>
                <h1
                    class="text-5xl font-bold mb-4 tracking-tight
                 bg-gradient-to-r from-red-600 to-orange-600
                 dark:from-red-400 dark:to-orange-400
                 bg-clip-text text-transparent"
                >
                    Scytale Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Shpionlar aslari — rulo atrofiga yozish
                </p>
            </div>

            <!-- Form -->
            <div class="w-full max-w-2xl mb-10 bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg">
                    {{ errorMessage }}
                </div>

                <div class="space-y-6">
                    <!-- Matn -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Matn (Plaintext yoki Ciphertext)
                        </label>
                        <textarea
                            v-model="plaintext"
                            @input="showGrid = false"
                            placeholder="Shifrlanadigan yoki deshifrlanadigan matnni kiriting..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                     focus:outline-none focus:border-red-500 dark:focus:border-red-400
                     transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Faqat A-Z harflari. Bo'shliqlar olib tashlanadi.
                        </p>
                    </div>

                    <!-- Rulo diametri -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Rulo diametri (ustunlar soni)
                        </label>
                        <input
                            v-model.number="diameter"
                            @input="showGrid = false"
                            type="number"
                            min="2"
                            max="20"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                     focus:outline-none focus:border-red-500 dark:focus:border-red-400
                     transition-all duration-300"
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Masalan: 5 → 5 ta ustun
                        </p>
                    </div>

                    <!-- Padding haqida -->
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-300 dark:border-amber-700 rounded-lg text-sm">
                        <p>
                            <strong>Padding:</strong> Matn uzunligi diametrga bo‘linmasa, <code>{{ paddingChar }}</code> bilan to‘ldiriladi va deshifrlashda olib tashlanadi.
                        </p>
                    </div>

                    <!-- Tugmalar -->
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-red-500 to-orange-600
                     hover:from-red-600 hover:to-orange-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Ruloga o'rash)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-orange-500 to-red-600
                     hover:from-orange-600 hover:to-red-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Deshifrlash (Ruloni ochish)
                        </button>
                    </div>

                    <!-- Jadval: Rulo ko'rinishi -->
                    <transition name="fade">
                        <div
                            v-if="showGrid && grid.length > 0"
                            class="mt-6 p-4 bg-gradient-to-br from-red-50 to-orange-50
                       dark:from-red-900/20 dark:to-orange-900/20 rounded-xl
                       border border-red-200 dark:border-red-800"
                        >
                            <h3 class="text-sm font-semibold text-red-700 dark:text-red-300 mb-3 text-center">
                                Rulo atrofiga yozilgan matn (ustun bo'yicha)
                            </h3>
                            <div class="grid gap-1" :style="{ gridTemplateColumns: `repeat(${diameter}, minmax(0, 1fr))` }">
                                <div
                                    v-for="(cell, i) in grid.flat()"
                                    :key="i"
                                    class="bg-white dark:bg-gray-800 p-2 rounded text-center border font-mono text-sm"
                                    :class="cell === paddingChar ? 'border-amber-400 dark:border-amber-600 text-amber-600 dark:text-amber-400' : 'border-red-300 dark:border-red-700 text-red-600 dark:text-red-400'"
                                >
                                    {{ cell || '·' }}
                                </div>
                            </div>
                            <p class="mt-3 text-center text-xs text-gray-600 dark:text-gray-400">
                                <code class="text-amber-600 dark:text-amber-400">{{ paddingChar }}</code> — to‘ldirish belgisi
                            </p>
                        </div>
                    </transition>

                    <!-- Natija -->
                    <div v-if="ciphertext">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Natija
                        </label>
                        <textarea
                            v-model="ciphertext"
                            readonly
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-mono
                     cursor-not-allowed"
                            rows="3"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- About tugmasi -->
            <button
                @click="toggleAbout"
                class="px-8 py-4 rounded-2xl font-medium text-white
               bg-gradient-to-r from-indigo-500 to-purple-600
               hover:from-indigo-600 hover:to-purple-700
               transition-all duration-300 shadow-md hover:shadow-lg mb-6"
            >
                About Scytale Cipher
            </button>

            <!-- About bo'limi -->
            <transition name="fade">
                <div
                    v-if="showAbout"
                    class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
                >
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                        Scytale Cipher haqida batafsil
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Scytale</strong> — qadimgi Yunonistonning harbiy shifrlash usuli. U <strong>rulo (silindr) atrofiga pergament o‘rab</strong>, ustun bo‘yicha matn yozish orqali ishlaydi.
                    </p>

                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-200 dark:border-red-800">
                            <h4 class="font-semibold text-red-700 dark:text-red-300">Miloddan avvalgi 5-asr</h4>
                            <p class="text-sm mt-1">Sparta, Yunoniston</p>
                        </div>
                        <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg border border-orange-200 dark:border-orange-800">
                            <h4 class="font-semibold text-orange-700 dark:text-orange-300">Rulo + Pergament</h4>
                            <p class="text-sm mt-1">Fizik vosita</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border border-amber-200 dark:border-amber-800">
                            <h4 class="font-semibold text-amber-700 dark:text-amber-300">Transpozitsiya</h4>
                            <p class="text-sm mt-1">Harflar joyi o'zgaradi</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">
                        Qanday ishlaydi?
                    </h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                        <li><strong>Rulo diametri</strong> — kalit (masalan, 5)</li>
                        <li><strong>Matn ustun bo‘yicha yoziladi</strong></li>
                        <li><strong>Rulo ochilganda — boshqa tartibda o‘qiladi</strong></li>
                        <li><strong>Qabul qiluvchi xuddi shu diametrli ruloga o‘raydi</strong></li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6">
                        <p><strong>Misol:</strong> Matn: <code>SALOM</code>, Diametr: <code>6</code></p>
                        <p class="mt-2">
                            S A L O M X<br>
                            ↓ (ruloni ochamiz)<br>
                            S X A L M O
                        </p>
                        <p class="mt-2 font-bold text-red-600 dark:text-red-400">
                            Natija: <code>SXALMO</code>
                        </p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Deshifrlash: <code>SXALMO</code> → <code>SALOM</code>
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50 dark:bg-green-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <h4 class="font-semibold text-green-700 dark:text-green-300 mb-2">Afzalliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Oddiy va tezkor</li>
                                <li>Fizik vosita — qo‘l bilan</li>
                                <li>O‘quv maqsadlari uchun ajoyib</li>
                            </ul>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">Kamchiliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Kalit (diametr) osongina taxmin qilinadi</li>
                                <li>Chastota tahlili bilan buziladi</li>
                                <li>Zamonaviy emas</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700">
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            <strong>Qiziqarli fakt:</strong> Scytale — tarixdagi birinchi transpozitsion shifr hisoblanadi.
                        </p>
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
