<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, columnar } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Columnar Transposition Cipher' },
];

const plaintext = ref('');
const key = ref('ZEBRAS'); // Kalit so'z
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');
const showGrid = ref(false);

// Kalit bo'yicha ustun tartibi
const columnOrder = computed(() => {
    const sorted = [...key.value.toUpperCase()].map((char, i) => ({ char, index: i }));
    sorted.sort((a, b) => a.char.localeCompare(b.char));
    return sorted.map(item => item.index);
});

// Grid: matnni ustunlarga joylashtirish
const grid = computed(() => {
    if (!plaintext.value || !key.value) return { rows: [], cols: [] };
    const text = plaintext.value.toUpperCase().replace(/[^A-Z]/g, '');
    const cols = key.value.length;
    const rows = Math.ceil(text.length / cols);
    const matrix: string[][] = Array(rows).fill(null).map(() => Array(cols).fill(''));

    let index = 0;
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            if (index < text.length) {
                matrix[r][c] = text[index++];
            }
        }
    }
    return { matrix, rows, cols };
});

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
    if ([...new Set(key.value.toUpperCase())].length !== key.value.length) {
        errorMessage.value = 'Kalitdagi harflar takrorlanmasligi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

// Shifrlash
function encrypt(): string {
    const { matrix, rows, cols } = grid.value;
    let result = '';

    for (const col of columnOrder.value) {
        for (let r = 0; r < rows; r++) {
            if (matrix[r][col]) {
                result += matrix[r][col];
            }
        }
    }
    return result;
}

// Deshifrlash
function decrypt(): string {
    const text = ciphertext.value.toUpperCase().replace(/[^A-Z]/g, '');
    const cols = key.value.length;
    const rows = Math.ceil(text.length / cols);
    const matrix: string[][] = Array(rows).fill(null).map(() => Array(cols).fill(''));

    let index = 0;
    for (const col of columnOrder.value) {
        for (let r = 0; r < rows; r++) {
            if (index < text.length) {
                matrix[r][col] = text[index++];
            }
        }
    }

    let result = '';
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            if (matrix[r][c]) result += matrix[r][c];
        }
    }
    return result;
}

function performEncrypt() {
    if (!validateInputs()) return;
    showGrid.value = true;
    ciphertext.value = encrypt();
}

function performDecrypt() {
    if (!validateInputs()) return;
    showGrid.value = true;
    ciphertext.value = decrypt();
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}
</script>

<template>
    <Head title="Columnar Transposition Cipher" />

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
                        class="w-16 h-16 mx-auto text-teal-600 dark:text-teal-400"
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
                 bg-gradient-to-r from-teal-600 to-cyan-600
                 dark:from-teal-400 dark:to-cyan-400
                 bg-clip-text text-transparent"
                >
                    Columnar Transposition Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Matn harflari ustunlar bo‘yicha joyini o‘zgartirish
                </p>
            </div>

            <!-- Form -->
            <div class="w-full max-w-3xl mb-10 bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
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
                     focus:outline-none focus:border-teal-500 dark:focus:border-teal-400
                     transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Faqat A-Z harflari. Bo'shliqlar olib tashlanadi.
                        </p>
                    </div>

                    <!-- Kalit -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Kalit so'z (masalan: ZEBRAS)
                        </label>
                        <input
                            v-model="key"
                            @input="showGrid = false"
                            type="text"
                            placeholder="ZEBRAS"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-mono uppercase
                     focus:outline-none focus:border-teal-500 dark:focus:border-teal-400
                     transition-all duration-300"
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Harflar takrorlanmasin. Tartib — kalit.
                        </p>
                    </div>

                    <!-- Tugmalar -->
                    <div class="flex space-x-4">
                        <button
                            @click="performEncrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-teal-500 to-cyan-600
                     hover:from-teal-600 hover:to-cyan-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash
                        </button>
                        <button
                            @click="performDecrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-cyan-500 to-teal-600
                     hover:from-cyan-600 hover:to-teal-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Deshifrlash
                        </button>
                    </div>

                    <!-- Jadval: Matn joylashuvi -->
                    <transition name="fade">
                        <div
                            v-if="showGrid && grid.matrix.length > 0"
                            class="mt-6 p-4 bg-gradient-to-br from-teal-50 to-cyan-50
                       dark:from-teal-900/20 dark:to-cyan-900/20 rounded-xl
                       border border-teal-200 dark:border-teal-800"
                        >
                            <h3 class="text-sm font-semibold text-teal-700 dark:text-teal-300 mb-3 text-center">
                                Matn jadvali (kalit bo‘yicha tartiblangan)
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs font-mono border-collapse">
                                    <thead>
                                    <tr>
                                        <th class="border border-teal-300 dark:border-teal-700 p-2 bg-teal-100 dark:bg-teal-900/50"></th>
                                        <th
                                            v-for="(char, i) in key.toUpperCase()"
                                            :key="i"
                                            class="border border-teal-300 dark:border-teal-700 p-2 bg-teal-100 dark:bg-teal-900/50"
                                        >
                                            <div class="flex flex-col items-center">
                                                <span class="font-bold text-teal-700 dark:text-teal-300">{{ char }}</span>
                                                <span class="text-xs text-gray-600 dark:text-gray-400">
                            #{{ columnOrder.value.indexOf(i) + 1 }}
                          </span>
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, r) in grid.matrix" :key="r">
                                        <td class="border border-teal-300 dark:border-teal-700 p-2 bg-teal-50 dark:bg-teal-900/30 font-bold">
                                            {{ r + 1 }}
                                        </td>
                                        <td
                                            v-for="(cell, c) in row"
                                            :key="c"
                                            class="border border-teal-300 dark:border-teal-700 p-2 text-center"
                                            :class="cell ? 'bg-white dark:bg-gray-800 text-teal-600 dark:text-teal-400' : 'bg-gray-100 dark:bg-gray-900 text-gray-400'"
                                        >
                                            {{ cell || '·' }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="mt-3 text-center text-xs text-gray-600 dark:text-gray-400">
                                Ustunlar kalit bo‘yicha tartiblanadi → o‘qish tartibi
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
                About Columnar Transposition
            </button>

            <!-- About bo'limi -->
            <transition name="fade">
                <div
                    v-if="showAbout"
                    class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
                >
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                        Columnar Transposition haqida batafsil
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Columnar Transposition</strong> — transpozitsion shifrlashning klassik usuli. Matn <strong>ustunlarga yoziladi</strong>, keyingi kalit so‘z bo‘yicha <strong>ustunlar tartiblanadi</strong>.
                    </p>

                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-teal-50 dark:bg-teal-900/20 p-4 rounded-lg border border-teal-200 dark:border-teal-800">
                            <h4 class="font-semibold text-teal-700 dark:text-teal-300">19-asr</h4>
                            <p class="text-sm mt-1">Harbiy aloqa</p>
                        </div>
                        <div class="bg-cyan-50 dark:bg-cyan-900/20 p-4 rounded-lg border border-cyan-200 dark:border-cyan-800">
                            <h4 class="font-semibold text-cyan-700 dark:text-cyan-300">Kalit so'z</h4>
                            <p class="text-sm mt-1">Masalan: ZEBRAS</p>
                        </div>
                        <div class="bg-sky-50 dark:bg-sky-900/20 p-4 rounded-lg border border-sky-200 dark:border-sky-800">
                            <h4 class="font-semibold text-sky-700 dark:text-sky-300">Transpozitsiya</h4>
                            <p class="text-sm mt-1">Joy o'zgartirish</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">
                        Qanday ishlaydi?
                    </h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                        <li><strong>Kalit so'z</strong> → ustun tartibi (A=1, B=2...)</li>
                        <li><strong>Matn ustunlarga yoziladi</strong></li>
                        <li><strong>Ustunlar tartiblanadi</strong> → natija o‘qiladi</li>
                        <li><strong>Deshifrlash</strong> — teskari tartib</li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6">
                        <p><strong>Misol:</strong> Matn: <code>WEAREDISCOVERED</code>, Kalit: <code>ZEBRAS</code></p>
                        <p class="mt-2 text-xs">
                            Z E B R A S<br>
                            6 2 1 4 5 3<br>
                            W E A R E D<br>
                            I S C O V E<br>
                            R E D _ _ _<br>
                            ↓ (tartib: 1,2,3,4,5,6)<br>
                            A C D E E _ R E D S O V E R E W
                        </p>
                        <p class="mt-2 font-bold text-teal-600 dark:text-teal-400">
                            Natija: <code>ACDEERVREDSOEEW</code> (to'ldirilgan)
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
                                <li>Bruteforce bilan buziladi</li>
                                <li>Zamonaviy emas</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700">
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            <strong>Eslatma:</strong> Bu shifr — "Route Cipher" yoki "Grille Cipher"ning oddiy versiyasi.
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
