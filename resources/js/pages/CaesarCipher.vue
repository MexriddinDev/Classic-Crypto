<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Caesar Cipher' },
];

const plaintext = ref('');
const shift = ref(3); // Standart 3
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');

function validateInputs(): boolean {
    if (!plaintext.value.trim()) {
        errorMessage.value = 'Matnni kiriting!';
        return false;
    }
    if (!/^[A-Za-z\s]+$/.test(plaintext.value)) {
        errorMessage.value = 'Matn faqat harflardan iborat bo\'lishi kerak!';
        return false;
    }
    if (shift.value < 0 || shift.value > 25) {
        errorMessage.value = 'Siljish 0 dan 25 gacha bo\'lishi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

function caesar(text: string, s: number, encrypt: boolean): string {
    const upperText = text.toUpperCase();
    let result = '';

    const direction = encrypt ? 1 : -1;
    const effectiveShift = (s * direction + 26) % 26;

    for (const char of upperText) {
        if (/[A-Z]/.test(char)) {
            const code = char.charCodeAt(0) - 65;
            const shifted = (code + effectiveShift) % 26;
            result += String.fromCharCode(shifted + 65);
        } else {
            result += char;
        }
    }

    return result;
}

function encrypt() {
    if (!validateInputs()) return;
    ciphertext.value = caesar(plaintext.value, shift.value, true);
}

function decrypt() {
    if (!validateInputs()) return;
    ciphertext.value = caesar(plaintext.value, shift.value, false);
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}
</script>

<template>
    <Head title="Caesar Cipher" />

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
                        class="w-16 h-16 mx-auto text-orange-600 dark:text-orange-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>
                </div>
                <h1
                    class="text-5xl font-bold mb-4 tracking-tight
                 bg-gradient-to-r from-orange-600 to-amber-600
                 dark:from-orange-400 dark:to-amber-400
                 bg-clip-text text-transparent"
                >
                    Caesar Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Harflar siljish orqali shifrlanadi
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
                            placeholder="Shifrlanadigan yoki deshifrlanadigan matnni kiriting..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                     focus:outline-none focus:border-orange-500 dark:focus:border-orange-400
                     transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Faqat A-Z harflari. Bo'shliqlar saqlanadi.
                        </p>
                    </div>

                    <!-- Siljish -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Siljish (Shift) — 0..25
                        </label>
                        <input
                            v-model.number="shift"
                            type="number"
                            min="0"
                            max="25"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                     focus:outline-none focus:border-orange-500 dark:focus:border-orange-400
                     transition-all duration-300"
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Standart: 3 (Julius Caesar ishlatgan)
                        </p>
                    </div>

                    <!-- Tugmalar -->
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-orange-500 to-amber-600
                     hover:from-orange-600 hover:to-amber-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Encrypt)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-amber-500 to-yellow-600
                     hover:from-amber-600 hover:to-yellow-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Deshifrlash (Decrypt)
                        </button>
                    </div>

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
                About Caesar Cipher
            </button>

            <!-- About bo'limi -->
            <transition name="fade">
                <div
                    v-if="showAbout"
                    class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
                >
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                        Caesar Cipher haqida batafsil
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Caesar Cipher</strong> — eng qadimgi shifrlash usullaridan biri. Rim imperatori <strong>Julius Caesar</strong> tomonidan ishlatilgan. Har bir harf <strong>belgilangan son (shift)</strong> qadar siljiladi.
                    </p>

                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg border border-orange-200 dark:border-orange-800">
                            <h4 class="font-semibold text-orange-700 dark:text-orange-300">Miloddan avvalgi 1-asr</h4>
                            <p class="text-sm mt-1">Julius Caesar</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border border-amber-200 dark:border-amber-800">
                            <h4 class="font-semibold text-amber-700 dark:text-amber-300">Shift = 3</h4>
                            <p class="text-sm mt-1">A→D, B→E, C→F</p>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <h4 class="font-semibold text-yellow-700 dark:text-yellow-300">26 ta kalit</h4>
                            <p class="text-sm mt-1">0..25 gacha</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">
                        Qanday ishlaydi?
                    </h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                        <li><strong>Siljish tanlanadi</strong>: Masalan, 3.</li>
                        <li><strong>Harf o'ngga siljiladi</strong>: <code>A → D</code>, <code>B → E</code>.</li>
                        <li><strong>Z dan keyin A</strong>: <code>X → A</code>, <code>Y → B</code>, <code>Z → C</code>.</li>
                        <li><strong>Deshifrlashda chapga</strong>: <code>D → A</code>.</li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6">
                        <p><strong>Misol:</strong> Matn: <code>HELLO</code>, Siljish: <code>3</code></p>
                        <p class="mt-2">
                            H → K<br>
                            E → H<br>
                            L → O<br>
                            L → O<br>
                            O → R
                        </p>
                        <p class="mt-2 font-bold text-green-600 dark:text-green-400">
                            Natija: <code>KHOOR</code>
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50 dark:bg-green-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <h4 class="font-semibold text-green-700 dark:text-green-300 mb-2">Afzalliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Oson tushunish</li>
                                <li>Tez amalga oshirish</li>
                                <li>O'quv maqsadlari uchun ideal</li>
                            </ul>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">Kamchiliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Fraqat 26 ta kalit</li>
                                <li>Brute force bilan 1 soniyada buziladi</li>
                                <li>Chastota tahlili bilan osongina ochiladi</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700">
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            <strong>Eslatma:</strong> Caesar Cipher — bu Monoalphabetic Cipherning maxsus holati (siljish bilan).
                        </p>
                    </div>

                    <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                        <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                            <p class="text-xs text-orange-700 dark:text-orange-300">Miloddan avvalgi 1-asr</p>
                            <p class="font-semibold">Ixtiro</p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg">
                            <p class="text-xs text-amber-700 dark:text-amber-300">Shift</p>
                            <p class="font-semibold">Kalit</p>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                            <p class="text-xs text-yellow-700 dark:text-yellow-300">26!</p>
                            <p class="font-semibold">Zaiflik</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <p class="text-xs text-purple-700 dark:text-purple-300">O'quv</p>
                            <p class="font-semibold">Maqsad</p>
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
