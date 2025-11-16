<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, atbash } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Atbash Cipher' },
];

const plaintext = ref('');
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');

// Alfavit teskari tartibda
const forward = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
const reverse = 'ZYXWVUTSRQPONMLKJIHGFEDCBA';

function validateInputs(): boolean {
    if (!plaintext.value.trim()) {
        errorMessage.value = 'Matnni kiriting!';
        return false;
    }
    if (!/^[A-Za-z\s]+$/.test(plaintext.value)) {
        errorMessage.value = 'Matn faqat A-Z harflardan iborat bo\'lishi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

// Atbash shifrlash (shifrlash va deshifrlash bir xil!)
function atbashCipher(text: string): string {
    const upperText = text.toUpperCase();
    let result = '';

    for (const char of upperText) {
        if (/[A-Z]/.test(char)) {
            const index = forward.indexOf(char);
            result += reverse[index];
        } else {
            result += char;
        }
    }

    return result;
}

function process() {
    if (!validateInputs()) return;
    ciphertext.value = atbashCipher(plaintext.value);
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}
</script>

<template>
    <Head title="Atbash Cipher" />

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
                        class="w-16 h-16 mx-auto text-purple-600 dark:text-purple-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 21v-4a3 3 0 013-3h12a3 3 0 013 3v4M3 21l18-18m0 18L3 3"
                        />
                    </svg>
                </div>
                <h1
                    class="text-5xl font-bold mb-4 tracking-tight
                 bg-gradient-to-r from-purple-600 to-pink-600
                 dark:from-purple-400 dark:to-pink-400
                 bg-clip-text text-transparent"
                >
                    Atbash Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Alfavitni teskari tartibda almashtirish
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
                     focus:outline-none focus:border-purple-500 dark:focus:border-purple-400
                     transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Faqat A-Z harflari. Bo'shliqlar saqlanadi.
                        </p>
                    </div>

                    <!-- Tugma (bitta — shifrlash va deshifrlash bir xil) -->
                    <div class="flex justify-center">
                        <button
                            @click="process"
                            class="px-8 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-purple-500 to-pink-600
                     hover:from-purple-600 hover:to-pink-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash / Deshifrlash
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

                    <!-- Jadval: Alfavit o'zgarishi -->
                    <div class="mt-6 p-4 bg-gradient-to-br from-purple-50 to-pink-50
                   dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl
                   border border-purple-200 dark:border-purple-800">
                        <h3 class="text-sm font-semibold text-purple-700 dark:text-purple-300 mb-3 text-center">
                            Alfavit o'zgarishi
                        </h3>
                        <div class="grid grid-cols-13 gap-1 text-xs font-mono">
                            <div
                                v-for="(f, i) in forward"
                                :key="i"
                                class="bg-white dark:bg-gray-800 p-1 rounded text-center border border-purple-300 dark:border-purple-700"
                            >
                                <span class="text-purple-600 dark:text-purple-400 font-bold">{{ f }}</span>
                                <span class="text-gray-400 mx-1">→</span>
                                <span class="text-pink-600 dark:text-pink-400 font-bold">{{ reverse[i] }}</span>
                            </div>
                        </div>
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
                About Atbash Cipher
            </button>

            <!-- About bo'limi -->
            <transition name="fade">
                <div
                    v-if="showAbout"
                    class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
                >
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                        Atbash Cipher haqida batafsil
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Atbash Cipher</strong> — eng qadimgi shifrlash usullaridan biri. U <strong>alfavitni teskari tartibda almashtiradi</strong>: A → Z, B → Y, va hokazo.
                    </p>

                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                            <h4 class="font-semibold text-purple-700 dark:text-purple-300">Miloddan avvalgi 500-yil</h4>
                            <p class="text-sm mt-1">Ibroniycha matnlarda</p>
                        </div>
                        <div class="bg-pink-50 dark:bg-pink-900/20 p-4 rounded-lg border border-pink-200 dark:border-pink-800">
                            <h4 class="font-semibold text-pink-700 dark:text-pink-300">Kalitsiz</h4>
                            <p class="text-sm mt-1">Faqat alfavit teskari</p>
                        </div>
                        <div class="bg-fuchsia-50 dark:bg-fuchsia-900/20 p-4 rounded-lg border border-fuchsia-200 dark:border-fuchsia-800">
                            <h4 class="font-semibold text-fuchsia-700 dark:text-fuchsia-300">Simmetrik</h4>
                            <p class="text-sm mt-1">Shifrlash = Deshifrlash</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">
                        Qanday ishlaydi?
                    </h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                        <li><strong>Alfavit teskari</strong>: <code>A→Z, B→Y, C→X, ..., Z→A</code></li>
                        <li><strong>Har bir harf almashtiriladi</strong></li>
                        <li><strong>Shifrlash va deshifrlash bir xil</strong></li>
                        <li><strong>Kalit yo‘q</strong> — faqat qoida</li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6">
                        <p><strong>Misol:</strong> Matn: <code>HELLO</code></p>
                        <p class="mt-2">
                            H → S<br>
                            E → V<br>
                            L → O<br>
                            L → O<br>
                            O → L
                        </p>
                        <p class="mt-2 font-bold text-purple-600 dark:text-purple-400">
                            Natija: <code>SVOOL</code>
                        </p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <em>Deshifrlash uchun ham xuddi shu: SVOOL → HELLO</em>
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50 dark:bg-green-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <h4 class="font-semibold text-green-700 dark:text-green-300 mb-2">Afzalliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Eng oddiy shifr</li>
                                <li>Kalit kerak emas</li>
                                <li>O‘quv maqsadlari uchun ideal</li>
                            </ul>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">Kamchiliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Juda zaif — 1 daqiqada buziladi</li>
                                <li>Chastota tahlili bilan osongina ochiladi</li>
                                <li>Zamonaviy emas</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700">
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            <strong>Qiziqarli fakt:</strong> Atbash nomi ibr. "alef-tav, bet-shin" dan kelib chiqqan — birinchi va oxirgi harflar.
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
.grid-cols-13 {
    grid-template-columns: repeat(13, minmax(0, 1fr));
}
</style>
