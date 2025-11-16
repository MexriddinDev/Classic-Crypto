<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, vigenere } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Vigenère Cipher' },
];

const plaintext = ref('');
const key = ref('LEMON'); // Standart kalit
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');

// Alfavit
const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

function validateInputs(): boolean {
    if (!plaintext.value.trim()) {
        errorMessage.value = 'Matnni kiriting!';
        return false;
    }
    if (!/^[A-Za-z\s]+$/.test(plaintext.value)) {
        errorMessage.value = 'Matn faqat A-Z harflardan iborat bo\'lishi kerak!';
        return false;
    }
    if (!key.value.trim()) {
        errorMessage.value = 'Kalit so\'zni kiriting!';
        return false;
    }
    if (!/^[A-Za-z]+$/.test(key.value)) {
        errorMessage.value = 'Kalit faqat harflardan iborat bo\'lishi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

// Vigenère shifrlash funksiyasi
function vigenereCipher(text: string, keyStr: string, encrypt: boolean): string {
    const upperText = text.toUpperCase();
    const upperKey = keyStr.toUpperCase().replace(/[^A-Z]/g, '');
    let result = '';
    let keyIndex = 0;

    for (const char of upperText) {
        if (/[A-Z]/.test(char)) {
            const textCode = char.charCodeAt(0) - 65;
            const keyCode = upperKey.charCodeAt(keyIndex % upperKey.length) - 65;
            let shifted: number;

            if (encrypt) {
                shifted = (textCode + keyCode) % 26;
            } else {
                shifted = (textCode - keyCode + 26) % 26;
            }

            result += String.fromCharCode(shifted + 65);
            keyIndex++;
        } else {
            result += char;
        }
    }

    return result;
}

function encrypt() {
    if (!validateInputs()) return;
    ciphertext.value = vigenereCipher(plaintext.value, key.value, true);
}

function decrypt() {
    if (!validateInputs()) return;
    ciphertext.value = vigenereCipher(plaintext.value, key.value, false);
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}

// Jadval: Kalit takrorlanishi
const keyTable = ref<string[]>([]);
function updateKeyTable() {
    if (!plaintext.value || !key.value) return;
    const upperText = plaintext.value.toUpperCase().replace(/[^A-Z]/g, '');
    const upperKey = key.value.toUpperCase().replace(/[^A-Z]/g, '');
    keyTable.value = [];

    let keyIndex = 0;
    for (let i = 0; i < plaintext.value.length; i++) {
        if (/[A-Za-z]/.test(plaintext.value[i])) {
            keyTable.value.push(upperKey[keyIndex % upperKey.length]);
            keyIndex++;
        } else {
            keyTable.value.push(' ');
        }
    }
}
</script>

<template>
    <Head title="Vigenère Cipher" />

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
                        class="w-16 h-16 mx-auto text-green-600 dark:text-green-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                        />
                    </svg>
                </div>
                <h1
                    class="text-5xl font-bold mb-4 tracking-tight
                 bg-gradient-to-r from-green-600 to-emerald-600
                 dark:from-green-400 dark:to-emerald-400
                 bg-clip-text text-transparent"
                >
                    Vigenère Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Polialfavit shifrlash usuli
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
                            @input="updateKeyTable"
                            placeholder="Shifrlanadigan yoki deshifrlanadigan matnni kiriting..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                     focus:outline-none focus:border-green-500 dark:focus:border-green-400
                     transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Faqat A-Z harflari. Bo'shliqlar saqlanadi.
                        </p>
                    </div>

                    <!-- Kalit -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Kalit so'z (masalan: LEMON)
                        </label>
                        <input
                            v-model="key"
                            @input="updateKeyTable"
                            type="text"
                            placeholder="LEMON"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-mono uppercase
                     focus:outline-none focus:border-green-500 dark:focus:border-green-400
                     transition-all duration-300"
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Faqat harflar. Takrorlanadi.
                        </p>
                    </div>

                    <!-- Kalit jadvali -->
                    <div v-if="keyTable.length > 0" class="mt-6 p-4 bg-gradient-to-br from-green-50 to-emerald-50
                   dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl
                   border border-green-200 dark:border-green-800">
                        <h3 class="text-sm font-semibold text-green-700 dark:text-green-300 mb-3 text-center">
                            Kalit takrorlanishi
                        </h3>
                        <div class="grid grid-cols-12 sm:grid-cols-16 md:grid-cols-20 gap-1 text-xs font-mono">
                            <div
                                v-for="(k, i) in keyTable"
                                :key="i"
                                class="bg-white dark:bg-gray-800 p-1 rounded text-center border"
                                :class="k === ' ' ? 'border-gray-200 dark:border-gray-700' : 'border-green-300 dark:border-green-700'"
                            >
                <span :class="k === ' ' ? 'text-gray-400' : 'text-green-600 dark:text-green-400 font-bold'">
                  {{ k }}
                </span>
                            </div>
                        </div>
                        <div class="mt-2 text-center text-xs text-gray-500 dark:text-gray-500">
                            Matn: <span class="font-mono">{{ plaintext.toUpperCase() }}</span>
                        </div>
                    </div>

                    <!-- Tugmalar -->
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-green-500 to-emerald-600
                     hover:from-green-600 hover:to-emerald-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Encrypt)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-emerald-500 to-teal-600
                     hover:from-emerald-600 hover:to-teal-700
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
                About Vigenère Cipher
            </button>

            <!-- About bo'limi -->
            <transition name="fade">
                <div
                    v-if="showAbout"
                    class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
                >
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                        Vigenère Cipher haqida batafsil
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Vigenère Cipher</strong> — 16-asrda Blaise de Vigenère tomonidan taklif qilingan <strong>polialfavit</strong> shifrlash usuli. U <strong>Caesar shifrining har xil siljishlarini birlashtiradi</strong>.
                    </p>

                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                            <h4 class="font-semibold text-green-700 dark:text-green-300">16-asr</h4>
                            <p class="text-sm mt-1">Blaise de Vigenère</p>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-lg border border-emerald-200 dark:border-emerald-800">
                            <h4 class="font-semibold text-emerald-700 dark:text-emerald-300">Kalit so'z</h4>
                            <p class="text-sm mt-1">Masalan: LEMON</p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-900/20 p-4 rounded-lg border border-teal-200 dark:border-teal-800">
                            <h4 class="font-semibold text-teal-700 dark:text-teal-300">Polialfavit</h4>
                            <p class="text-sm mt-1">Har xil siljish</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">
                        Qanday ishlaydi?
                    </h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                        <li><strong>Kalit takrorlanadi</strong>: <code>LEMONLEMON...</code></li>
                        <li><strong>Har bir harf uchun siljish</strong>: <code>L=11, E=4, M=12</code></li>
                        <li><strong>Shifrlash</strong>: <code>C = (P + K) mod 26</code></li>
                        <li><strong>Deshifrlash</strong>: <code>P = (C - K) mod 26</code></li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6">
                        <p><strong>Misol:</strong> Matn: <code>ATTACK</code>, Kalit: <code>LEMON</code></p>
                        <p class="mt-2">
                            A T T A C K<br>
                            L E M O N L<br>
                            ↓ ↓ ↓ ↓ ↓ ↓<br>
                            L X F O P V
                        </p>
                        <p class="mt-2 font-bold text-green-600 dark:text-green-400">
                            Natija: <code>LXFOPV</code>
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50 dark:bg-green-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <h4 class="font-semibold text-green-700 dark:text-green-300 mb-2">Afzalliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Chastota tahliliga chidamli</li>
                                <li>Oson amalga oshirish</li>
                                <li>Kalit uzun bo'lsa — kuchli</li>
                            </ul>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">Kamchiliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Kalit qisqa bo'lsa — zaif</li>
                                <li>Kasiski testi bilan buziladi</li>
                                <li>Zamonaviy standartlarga mos emas</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700">
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            <strong>Eslatma:</strong> Vigenère — "le chiffrage indéchiffrable" deb atalgan, lekin 19-asrda buzilgan.
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
.grid-cols-20 {
    grid-template-columns: repeat(20, minmax(0, 1fr));
}
</style>
