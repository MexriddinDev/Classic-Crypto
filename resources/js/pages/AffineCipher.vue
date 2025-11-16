<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, affine } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Affine Cipher' },
];

const plaintext = ref('');
const a = ref(5); // a = 5 (mod 26 bilan o'zaro tub)
const b = ref(8); // b = 8
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');

// Alfavit
const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

// a ning mod 26 bilan o'zaro tub bo'lishi kerak (1,3,5,7,9,11,15,17,19,21,23,25)
const validAValues = [1, 3, 5, 7, 9, 11, 15, 17, 19, 21, 23, 25];

function gcd(x: number, y: number): number {
    return y === 0 ? x : gcd(y, x % y);
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
    if (!validAValues.includes(a.value)) {
        errorMessage.value = `a qiymati ${validAValues.join(', ')} bo'lishi kerak!`;
        return false;
    }
    if (b.value < 0 || b.value > 25) {
        errorMessage.value = 'b qiymati 0 dan 25 gacha bo\'lishi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

// Modulyar teskari (Extended Euclidean)
function modInverse(a: number, m: number): number {
    for (let x = 1; x < m; x++) {
        if ((a * x) % m === 1) return x;
    }
    return 1;
}

// Affine shifrlash
function affineCipher(text: string, aVal: number, bVal: number, encrypt: boolean): string {
    const upperText = text.toUpperCase();
    let result = '';
    const m = 26;

    for (const char of upperText) {
        if (/[A-Z]/.test(char)) {
            const p = char.charCodeAt(0) - 65;
            let c: number;

            if (encrypt) {
                c = (aVal * p + bVal) % m;
            } else {
                const aInv = modInverse(aVal, m);
                c = (aInv * (p - bVal + m)) % m;
            }

            result += String.fromCharCode(c + 65);
        } else {
            result += char;
        }
    }

    return result;
}

function encrypt() {
    if (!validateInputs()) return;
    ciphertext.value = affineCipher(plaintext.value, a.value, b.value, true);
}

function decrypt() {
    if (!validateInputs()) return;
    ciphertext.value = affineCipher(plaintext.value, a.value, b.value, false);
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}

// a qiymati uchun tavsiya
const suggestedA = computed(() => {
    return validAValues.filter(x => x !== a.value).slice(0, 3);
});
</script>

<template>
    <Head title="Affine Cipher" />

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
                        class="w-16 h-16 mx-auto text-lime-600 dark:text-lime-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4m-6 0a2 2 0 01-2 2H9a2 2 0 01-2-2V5a2 2 0 012-2h2a2 2 0 012 2z"
                        />
                    </svg>
                </div>
                <h1
                    class="text-5xl font-bold mb-4 tracking-tight
                 bg-gradient-to-r from-lime-600 to-green-600
                 dark:from-lime-400 dark:to-green-400
                 bg-clip-text text-transparent"
                >
                    Affine Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Matematik shifrlash: C = (a×P + b) mod 26
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
                     focus:outline-none focus:border-lime-500 dark:focus:border-lime-400
                     transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Faqat A-Z harflari. Bo'shliqlar saqlanadi.
                        </p>
                    </div>

                    <!-- a va b -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                                a (ko'paytirish) — o'zaro tub
                            </label>
                            <input
                                v-model.number="a"
                                type="number"
                                min="1"
                                max="25"
                                class="w-full px-4 py-3 rounded-lg border-2
                       bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                       focus:outline-none focus:border-lime-500 dark:focus:border-lime-400
                       transition-all duration-300"
                                :class="validAValues.includes(a) ? 'border-gray-200 dark:border-gray-700' : 'border-red-500'"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Ruxsat etilgan: {{ validAValues.join(', ') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                                b (siljish) — 0..25
                            </label>
                            <input
                                v-model.number="b"
                                type="number"
                                min="0"
                                max="25"
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                       bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                       focus:outline-none focus:border-lime-500 dark:focus:border-lime-400
                       transition-all duration-300"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Standart: 8
                            </p>
                        </div>
                    </div>

                    <!-- Tavsiya -->
                    <div v-if="!validAValues.includes(a)" class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 rounded-lg">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            <strong>Tavsiya:</strong> a uchun {{ suggestedA.join(', ') }} tanlang.
                        </p>
                    </div>

                    <!-- Tugmalar -->
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-lime-500 to-green-600
                     hover:from-lime-600 hover:to-green-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Encrypt)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-green-500 to-emerald-600
                     hover:from-green-600 hover:to-emerald-700
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
                About Affine Cipher
            </button>

            <!-- About bo'limi -->
            <transition name="fade">
                <div
                    v-if="showAbout"
                    class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
                >
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                        Affine Cipher haqida batafsil
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Affine Cipher</strong> — Caesar shifrining matematik kengaytmasi. U <strong>ko‘paytirish</strong> va <strong>siljish</strong>ni birlashtiradi.
                    </p>

                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-lime-50 dark:bg-lime-900/20 p-4 rounded-lg border border-lime-200 dark:border-lime-800">
                            <h4 class="font-semibold text-lime-700 dark:text-lime-300">15-asr</h4>
                            <p class="text-sm mt-1">Matematik shifr</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                            <h4 class="font-semibold text-green-700 dark:text-green-300">a × P + b</h4>
                            <p class="text-sm mt-1">mod 26</p>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-lg border border-emerald-200 dark:border-emerald-800">
                            <h4 class="font-semibold text-emerald-700 dark:text-emerald-300">a o'zaro tub</h4>
                            <p class="text-sm mt-1">gcd(a,26)=1</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">
                        Qanday ishlaydi?
                    </h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                        <li><strong>Shifrlash</strong>: <code>C = (a × P + b) mod 26</code></li>
                        <li><strong>Deshifrlash</strong>: <code>P = a⁻¹ × (C - b) mod 26</code></li>
                        <li><strong>a⁻¹</strong> — modulyar teskari</li>
                        <li><code>a</code> va 26 o'zaro tub bo'lishi kerak</li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6">
                        <p><strong>Misol:</strong> Matn: <code>HELLO</code>, a=<code>5</code>, b=<code>8</code></p>
                        <p class="mt-2">
                            H=7 → (5×7 + 8) mod 26 = 43 mod 26 = 17 → R<br>
                            E=4 → (5×4 + 8) mod 26 = 28 mod 26 = 2 → C<br>
                            L=11 → (5×11 + 8) mod 26 = 63 mod 26 = 11 → L<br>
                            L=11 → 11 → L<br>
                            O=14 → (5×14 + 8) mod 26 = 78 mod 26 = 0 → A
                        </p>
                        <p class="mt-2 font-bold text-green-600 dark:text-green-400">
                            Natija: <code>RCLLA</code>
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50 dark:bg-green-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <h4 class="font-semibold text-green-700 dark:text-green-300 mb-2">Afzalliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Caesar'dan kuchliroq</li>
                                <li>Matematik asos</li>
                                <li>O'quv maqsadlari uchun ideal</li>
                            </ul>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">Kamchiliklari</h4>
                            <ul class="text-sm space-y-1">
                                <li>Fraqat 312 ta kalit</li>
                                <li>Chastota tahlili bilan buziladi</li>
                                <li>Zamonaviy emas</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700">
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            <strong>Eslatma:</strong> Affine — Monoalphabetic Cipherning maxsus holati.
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
