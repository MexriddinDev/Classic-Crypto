<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Hill Cipher' },
];

const plaintext = ref('');
const keyMatrix = ref('5,3,8,5'); // 2x2 matrix: [[5,3],[8,5]]
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');

function parseKeyMatrix(): number[][] | null {
    const parts = keyMatrix.value.split(',').map(s => parseInt(s.trim()));
    if (parts.length !== 4 || parts.some(n => isNaN(n) || n < 0 || n > 25)) {
        return null;
    }
    return [
        [parts[0], parts[1]],
        [parts[2], parts[3]]
    ];
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
    const matrix = parseKeyMatrix();
    if (!matrix) {
        errorMessage.value = 'Kalit matritsasi noto\'g\'ri! 4 ta son kiriting (0-25), vergul bilan ajratilgan.';
        return false;
    }
    const det = (matrix[0][0] * matrix[1][1] - matrix[0][1] * matrix[1][0] + 26) % 26;
    if (gcd(det, 26) !== 1) {
        errorMessage.value = 'Kalit matritsasi teskari bo\'lishi kerak! Determinant (mod 26) 26 bilan o\'zaro tub bo\'lishi kerak.';
        return false;
    }
    errorMessage.value = '';
    return true;
}

function gcd(a: number, b: number): number {
    return b === 0 ? a : gcd(b, a % b);
}

function modInverse(a: number, m: number): number {
    for (let x = 1; x < m; x++) {
        if ((a * x) % m === 1) return x;
    }
    return 1;
}

function matrixMultiply(matrix: number[][], vector: number[]): number[] {
    return [
        (matrix[0][0] * vector[0] + matrix[0][1] * vector[1]) % 26,
        (matrix[1][0] * vector[0] + matrix[1][1] * vector[1]) % 26
    ];
}

function matrixInverse(matrix: number[][]): number[][] {
    const det = (matrix[0][0] * matrix[1][1] - matrix[0][1] * matrix[1][0] + 26) % 26;
    const detInv = modInverse(det, 26);
    return [
        [(matrix[1][1] * detInv) % 26, ((-matrix[0][1] + 26) * detInv) % 26],
        [((-matrix[1][0] + 26) * detInv) % 26, (matrix[0][0] * detInv) % 26]
    ];
}

function processText(text: string, isEncrypt: boolean): string {
    const matrix = parseKeyMatrix()!;
    const invMatrix = isEncrypt ? null : matrixInverse(matrix);
    const cleanText = text.toUpperCase().replace(/[^A-Z]/g, '');

    if (cleanText.length % 2 !== 0) {
        cleanText += 'X'; // Padding
    }

    let result = '';
    for (let i = 0; i < cleanText.length; i += 2) {
        const p1 = cleanText.charCodeAt(i) - 65;
        const p2 = cleanText.charCodeAt(i + 1) - 65;
        const vector = [p1, p2];

        const processed = isEncrypt
            ? matrixMultiply(matrix, vector)
            : matrixMultiply(invMatrix!, vector);

        result += String.fromCharCode(processed[0] + 65) + String.fromCharCode(processed[1] + 65);
    }

    return result;
}

function encrypt() {
    if (!validateInputs()) return;
    ciphertext.value = processText(plaintext.value, true);
}

function decrypt() {
    if (!validateInputs()) return;
    ciphertext.value = processText(plaintext.value, false);
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}
</script>

<template>
    <Head title="Hill Cipher" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen flex flex-col items-center justify-start py-12 px-6
                bg-gradient-to-br from-gray-50 via-white to-gray-100
                dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-950
                rounded-2xl shadow-xl transition-colors duration-300">

            <!-- Header Section -->
            <div class="text-center mb-12 max-w-3xl">
                <div class="mb-4 inline-block">
                    <svg class="w-16 h-16 mx-auto text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4 tracking-tight
                       bg-gradient-to-r from-yellow-600 to-amber-600
                       dark:from-yellow-400 dark:to-amber-400
                       bg-clip-text text-transparent">
                    Hill Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Matritsali modulyar algebra asosida shifrlash
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
                            placeholder="Shifrlanadigan yoki deshifrlanadigan matnni kiriting (faqat harflar)..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                               focus:outline-none focus:border-yellow-500 dark:focus:border-yellow-400
                               transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Bo'shliqlar olib tashlanadi. Juft bo'lmagan uzunlikka "X" qo'shiladi.</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">Kalit Matritsasi (2×2)</label>
                        <input
                            v-model="keyMatrix"
                            type="text"
                            placeholder="5,3,8,5 (vergul bilan ajratilgan 4 ta son, 0-25)"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                               focus:outline-none focus:border-yellow-500 dark:focus:border-yellow-400
                               transition-all duration-300"
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Masalan: 5,3,8,5 → [[5,3],[8,5]]</p>
                    </div>
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                               bg-gradient-to-r from-yellow-500 to-amber-600
                               hover:from-yellow-600 hover:to-amber-700
                               transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Encrypt)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                               bg-gradient-to-r from-orange-500 to-red-600
                               hover:from-orange-600 hover:to-red-700
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
                About Hill Cipher
            </button>

            <!-- About Section (Collapsible) -->
            <transition name="fade">
                <div v-if="showAbout" class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Hill Cipher haqida batafsil</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Hill Cipher — 1929-yilda Lester S. Hill tomonidan ixtiro qilingan polialfavitik shifrlash usuli boʻlib, chiziqli algebra va matritsalar asosida ishlaydi. U harflarni juft-juft olib, 2×2 kalit matritsasi yordamida modulyar arifmetika orqali shifrlaydi.
                    </p>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Qanday shifrlaydi?</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        1. <strong>Matn tayyorlanadi</strong>: Barcha bo'shliqlar va belgilar olib tashlanadi. Uzunlik juft bo'lmagan bo'lsa, oxiriga "X" qo'shiladi.<br>
                        2. <strong>Harflar raqamlarga aylantiriladi</strong>: A=0, B=1, ..., Z=25.<br>
                        3. <strong>Juft harflar vektor sifatida olinadi</strong>: [P₁, P₂].<br>
                        4. <strong>Kalit matritsasi K = [[a,b],[c,d]] bilan ko'paytiriladi</strong>:<br>
                        &nbsp;&nbsp;&nbsp;C = K × P (mod 26)<br>
                        5. Natijada shifrlangan juft harflar olinadi.
                    </p>
                    <p class="text-sm font-mono bg-gray-100 dark:bg-gray-900 p-3 rounded-lg mb-4">
                        Misol: K = [[5,3],[8,5]], P = "HE" → [7,4]<br>
                        C = [[5,3],[8,5]] × [7,4] = [47,76] → [21,24] (mod 26) → "VU"
                    </p>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Qanday deshifrlaydi?</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        1. Kalit matritsasining teskarisi K⁻¹ topiladi (mod 26 bo'yicha).<br>
                        2. P = K⁻¹ × C (mod 26).<br>
                        3. Raqamlar harflarga qaytariladi.
                    </p>
                    <p class="text-gray-600 dark:text-gray-400">
                        <strong>Muhim:</strong> Kalit matritsasi teskari bo'lishi kerak, ya'ni determinant (mod 26) 26 bilan o'zaro tub bo'lishi kerak. Bu shifrlashning xavfsizligi va deshifrlash imkoniyatini ta'minlaydi.
                    </p>
                    <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                        <p class="text-sm text-amber-800 dark:text-amber-300">
                            <strong>Eslatma:</strong> Hill Cipher chastota tahliliga chidamli, lekin "known plaintext attack" orqali buzilishi mumkin. Zamonaviy standartlarda xavfsiz emas.
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
