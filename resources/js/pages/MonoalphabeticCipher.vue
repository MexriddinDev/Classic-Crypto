<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Monoalphabetic Cipher' },
];

const plaintext = ref('');
const key = ref(''); // Misol: p-x, e-z
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');

// Standart A-Z
const defaultAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

// Kalitni parse qilish (faqat "-" bilan)
function parseKey(): Map<string, string> | null {
    const map = new Map<string, string>();
    const used = new Set<string>();

    if (!key.value.trim()) return null;

    const pairs = key.value.toUpperCase().split(',').map(s => s.trim());
    for (const pair of pairs) {
        if (!pair.includes('-')) {
            errorMessage.value = 'Kalit formati: p-x, e-z (faqat chiziqcha bilan)';
            return null;
        }

        const [from, to] = pair.split('-').map(s => s.trim());
        if (from.length !== 1 || to.length !== 1 || !/[A-Z]/.test(from) || !/[A-Z]/.test(to)) {
            errorMessage.value = 'Harflar faqat A-Z bo\'lishi kerak!';
            return null;
        }
        if (used.has(to)) {
            errorMessage.value = `Bir xil harfga ikki marta belgi berilgan: ${to}`;
            return null;
        }
        map.set(from, to);
        used.add(to);
    }
    return map;
}

// To'liq kalit yaratish
function generateFullKey(customMap: Map<string, string> | null): Map<string, string> {
    const fullMap = new Map<string, string>();
    const used = new Set<string>(customMap ? Array.from(customMap.values()) : []);

    let remainingFrom = defaultAlphabet.split('').filter(c => !customMap?.has(c));
    let remainingTo = defaultAlphabet.split('').filter(c => !used.has(c));

    // Tasodifiy aralashtirish
    for (let i = remainingTo.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [remainingTo[i], remainingTo[j]] = [remainingTo[j], remainingTo[i]];
    }

    let idx = 0;
    for (const from of remainingFrom) {
        fullMap.set(from, remainingTo[idx++]);
    }

    if (customMap) {
        for (const [from, to] of customMap) {
            fullMap.set(from, to);
        }
    }

    return fullMap;
}

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

function encrypt() {
    if (!validateInputs()) return;

    const customMap = parseKey();
    if (customMap === null && key.value.trim()) return;

    const fullMap = generateFullKey(customMap);
    const upperText = plaintext.value.toUpperCase();
    let result = '';

    for (const char of upperText) {
        if (/[A-Z]/.test(char)) {
            result += fullMap.get(char) || char;
        } else {
            result += char;
        }
    }

    ciphertext.value = result;
}

function decrypt() {
    if (!validateInputs()) return;

    const customMap = parseKey();
    if (customMap === null && key.value.trim()) return;

    const fullMap = generateFullKey(customMap);
    const reverseMap = new Map<string, string>();
    for (const [k, v] of fullMap) {
        reverseMap.set(v, k);
    }

    const upperText = plaintext.value.toUpperCase();
    let result = '';

    for (const char of upperText) {
        if (/[A-Z]/.test(char)) {
            result += reverseMap.get(char) || char;
        } else {
            result += char;
        }
    }

    ciphertext.value = result;
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}

// Jadval
const keyTable = computed(() => {
    const customMap = parseKey();
    if (customMap === null && key.value.trim()) return [];

    const fullMap = generateFullKey(customMap);
    return Array.from(fullMap.entries()).map(([from, to]) => ({ from, to }));
});
</script>

<template>
    <Head title="Monoalphabetic Cipher" />

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
                        class="w-16 h-16 mx-auto text-emerald-600 dark:text-emerald-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 7h2l3 3 3-3h2m-6 6h6m-6 6h6m-9-6v6"
                        />
                    </svg>
                </div>
                <h1
                    class="text-5xl font-bold mb-4 tracking-tight
                 bg-gradient-to-r from-emerald-600 to-teal-600
                 dark:from-emerald-400 dark:to-teal-400
                 bg-clip-text text-transparent"
                >
                    Monoalphabetic Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Har bir harf — doimiy belgi
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
                     focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-400
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
                            Kalit (ixtiyoriy)
                        </label>
                        <input
                            v-model="key"
                            type="text"
                            placeholder="p-x, e-z (faqat chiziqcha bilan)"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                     focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-400
                     transition-all duration-300"
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Bo'sh qoldirsangiz — tasodifiy kalit yaratiladi.
                        </p>
                    </div>

                    <!-- Jadval -->
                    <div
                        v-if="keyTable.length > 0"
                        class="mt-6 p-4 bg-gradient-to-br from-emerald-50 to-teal-50
                   dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl
                   border border-emerald-200 dark:border-emerald-800"
                    >
                        <h3 class="text-sm font-semibold text-emerald-700 dark:text-emerald-300 mb-3 text-center">
                            Almashtirish Jadvali
                        </h3>
                        <div class="grid grid-cols-13 gap-1 text-xs font-mono">
                            <div
                                v-for="item in keyTable"
                                :key="item.from"
                                class="bg-white dark:bg-gray-800 p-1 rounded text-center border border-emerald-200 dark:border-emerald-700"
                            >
                                <div class="font-bold text-emerald-600 dark:text-emerald-400">{{ item.from }}</div>
                                <div class="text-xs">→</div>
                                <div class="font-bold text-teal-600 dark:text-teal-400">{{ item.to }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tugmalar -->
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-emerald-500 to-teal-600
                     hover:from-emerald-600 hover:to-teal-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Encrypt)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-teal-500 to-cyan-600
                     hover:from-teal-600 hover:to-cyan-700
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
                About Monoalphabetic Cipher
            </button>

            <!-- About bo'limi -->
            <transition name="fade">
                <div
                    v-if="showAbout"
                    class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
                >
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                        Monoalphabetic Cipher haqida batafsil
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Monoalphabetic Substitution</strong> — eng oddiy shifrlash usuli.
                        Har bir harf <strong>doimiy ravishda</strong> boshqa harfga almashtiriladi.
                    </p>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-red-50 dark:bg-red-900/20 p-5 rounded-xl border border-red-200 dark:border-red-800">
                            <h3 class="font-semibold text-red-700 dark:text-red-300 mb-2">Caesar</h3>
                            <p class="text-sm"><code>A→D, B→E</code> (3 siljish)</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800">
                            <h3 class="font-semibold text-green-700 dark:text-green-300 mb-2">Mono</h3>
                            <p class="text-sm"><code>p-x, e-z</code> (tasodifiy)</p>
                        </div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6">
                        <p><strong>Misol:</strong> Kalit: <code>p-x, e-z</code></p>
                        <p class="mt-2">
                            P → X<br>
                            E → Z<br>
                            H → M (tasodifiy)<br>
                            L → R<br>
                            O → I
                        </p>
                        <p class="mt-2 font-bold text-green-600 dark:text-green-400">
                            HELLO → MZRRI
                        </p>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700 mb-6">
                        <h4 class="font-semibold text-amber-800 dark:text-amber-200 mb-2">Afzallik</h4>
                        <p class="text-sm">Oson tushunish, dasturlashda qulay</p>
                    </div>

                    <div class="bg-red-50 dark:bg-red-900/30 p-5 rounded-xl border border-red-300 dark:border-red-700">
                        <h4 class="font-semibold text-red-800 dark:text-red-200 mb-2">Kamchilik</h4>
                        <p class="text-sm">Chastota tahlili bilan osongina buziladi</p>
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
