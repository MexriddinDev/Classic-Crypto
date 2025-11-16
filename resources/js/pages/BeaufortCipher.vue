<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Beaufort Cipher' },
];

const plaintext = ref('');
const key = ref('');
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');

function validateInputs(): boolean {
    if (!plaintext.value.trim()) {
        errorMessage.value = 'Matnni kiriting!';
        return false;
    }
    if (!key.value.trim()) {
        errorMessage.value = 'Kalitni kiriting!';
        return false;
    }
    if (!/^[A-Za-z]+$/.test(key.value)) {
        errorMessage.value = 'Kalit faqat harflardan iborat bo\'lishi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

function encrypt() {
    if (!validateInputs()) return;

    const upperPlain = plaintext.value.toUpperCase();
    const upperKey = key.value.toUpperCase();
    let result = '';
    let keyIndex = 0;

    for (let i = 0; i < upperPlain.length; i++) {
        const char = upperPlain[i];
        if (char >= 'A' && char <= 'Z') {
            const k = upperKey[keyIndex % upperKey.length].charCodeAt(0) - 65;
            const p = char.charCodeAt(0) - 65;
            // Beaufort: C = K - P (mod 26)
            const encrypted = (k - p + 26) % 26;
            result += String.fromCharCode(encrypted + 65);
            keyIndex++;
        } else {
            result += char;
        }
    }

    ciphertext.value = result;
}

function decrypt() {
    // Beaufort is its own inverse: Encrypt = Decrypt
    encrypt();
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}
</script>

<template>
    <Head title="Beaufort Cipher" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen flex flex-col items-center justify-start py-12 px-6
                bg-gradient-to-br from-gray-50 via-white to-gray-100
                dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-950
                rounded-2xl shadow-xl transition-colors duration-300">

            <!-- Header Section -->
            <div class="text-center mb-12 max-w-3xl">
                <div class="mb-4 inline-block">
                    <svg class="w-16 h-16 mx-auto text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4 tracking-tight
                       bg-gradient-to-r from-red-600 to-rose-600
                       dark:from-red-400 dark:to-rose-400
                       bg-clip-text text-transparent">
                    Beaufort Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Vigenèrega o'xshash, lekin teskari yo'nalishda
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
                               focus:outline-none focus:border-red-500 dark:focus:border-red-400
                               transition-all duration-300"
                            rows="4"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">Kalit (Key)</label>
                        <input
                            v-model="key"
                            type="text"
                            placeholder="Kalit so'zni kiriting (faqat harflar)..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                               focus:outline-none focus:border-red-500 dark:focus:border-red-400
                               transition-all duration-300"
                        />
                    </div>
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                               bg-gradient-to-r from-red-500 to-rose-600
                               hover:from-red-600 hover:to-rose-700
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
                               bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white
                               cursor-not-allowed"
                            rows="4"
                        ></textarea>
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
                About Beaufort Cipher
            </button>

            <!-- About Section (Collapsible) -->
            <transition name="fade">
                <div v-if="showAbout" class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Beaufort Cipher haqida batafsil</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Beaufort Cipher — 1857-yilda ingliz admirali <strong>Ser Frensis Bofort</strong> tomonidan ixtiro qilingan polialfavitik shifrlash usuli. U Vigenère cipherning <strong>teskari varianti</strong> hisoblanadi.
                    </p>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <h3 class="font-semibold text-red-700 dark:text-red-300 mb-2">Vigenère (Oddiy)</h3>
                            <p class="text-sm"><code>C = (P + K) mod 26</code></p>
                            <p class="text-xs mt-1">Kalit <strong>qo‘shiladi</strong></p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-5 rounded-xl border border-purple-200 dark:border-purple-800">
                            <h3 class="font-semibold text-purple-700 dark:text-purple-300 mb-2">Beaufort (Teskari)</h3>
                            <p class="text-sm"><code>C = (K - P) mod 26</code></p>
                            <p class="text-xs mt-1">Kalitdan <strong>ayiriladi</strong></p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Qanday ishlaydi?</h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-4">
                        <li>Kalit matn uzunligiga mos takrorlanadi.</li>
                        <li>Har bir harf raqamga aylantiriladi (A=0, Z=25).</li>
                        <li><strong>Formula</strong>: <code>C = (K - P + 26) mod 26</code></li>
                        <li>Natija harfga qaytariladi.</li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg font-mono text-sm mb-4">
                        <p><strong>Misol:</strong> Matn: <code>HI</code>, Kalit: <code>KEY</code></p>
                        <p class="mt-2">
                            H(7) → K(10) - 7 = 3 → <code>D</code><br>
                            I(8) → E(4) - 8 = -4 + 26 = 22 → <code>W</code>
                        </p>
                        <p class="mt-2 font-bold text-green-600 dark:text-green-400">Natija: <code>DW</code></p>
                    </div>

                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Eng muhimi: O‘z-o‘zini teskari qiladi!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        <strong>Shifrlash = Deshifrlash</strong><br>
                        Xuddi shu kalit va matn bilan ikki marta shifrlasangiz — asl matn qaytadi!
                    </p>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-4 rounded-lg border border-amber-300 dark:border-amber-700">
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            <strong>Diqqat:</strong> Beaufort cipher Vigenère kabi chastota tahliliga chidamli, lekin kalit uzunligi ma'lum bo'lsa — Kasiski usuli bilan buziladi.
                        </p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                            <p class="text-xs text-green-700 dark:text-green-300">1857-yil</p>
                            <p class="font-semibold">Ixtiro qilingan</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                            <p class="text-xs text-blue-700 dark:text-blue-300">Polialfavitik</p>
                            <p class="font-semibold">Tur</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg">
                            <p class="text-xs text-purple-700 dark:text-purple-300">K = C</p>
                            <p class="font-semibold">O‘z teskarisi</p>
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
