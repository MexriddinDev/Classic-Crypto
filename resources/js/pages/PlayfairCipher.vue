<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Playfair Cipher' },
];

const plaintext = ref('');
const key = ref('');
const ciphertext = ref('');
const showAbout = ref(false);
const errorMessage = ref('');
const matrix = ref<string[][]>([]);

/* -------------------------------------------------
   5×5 Playfair matritsasini yaratish
   ------------------------------------------------- */
function generatePlayfairMatrix(keyStr: string): string[][] {
    const alphabet = 'ABCDEFGHIKLMNOPQRSTUVWXYZ'; // J yoʻq
    const used = new Set<string>();
    const result: string[] = [];

    // Kalitni qoʻshamiz
    const upperKey = keyStr.toUpperCase().replace(/J/g, 'I');
    for (const char of upperKey) {
        if (/[A-Z]/.test(char) && !used.has(char)) {
            used.add(char);
            result.push(char);
        }
    }

    // Qolgan harflarni qoʻshamiz
    for (const char of alphabet) {
        if (!used.has(char)) result.push(char);
    }

    // 5×5 matritsa
    const mat: string[][] = [];
    for (let i = 0; i < 5; i++) {
        mat[i] = result.slice(i * 5, i * 5 + 5);
    }
    return mat;
}

/* -------------------------------------------------
   Harfning (row, col) pozitsiyasini topish
   ------------------------------------------------- */
function findPosition(mat: string[][], ch: string): [number, number] {
    const c = ch.toUpperCase() === 'J' ? 'I' : ch.toUpperCase();
    for (let r = 0; r < 5; r++) {
        for (let c = 0; c < 5; c++) {
            if (mat[r][c] === c) return [r, c];
        }
    }
    return [0, 0];
}

/* -------------------------------------------------
   Input tekshiruvi
   ------------------------------------------------- */
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
        errorMessage.value = 'Kalit faqat harflardan iborat boʻlishi kerak!';
        return false;
    }
    errorMessage.value = '';
    return true;
}

/* -------------------------------------------------
   Matnni juftliklarga tayyorlash
   ------------------------------------------------- */
function prepareText(text: string): string {
    let cleaned = text.toUpperCase().replace(/J/g, 'I').replace(/[^A-Z]/g, '');
    let result = '';
    let i = 0;

    while (i < cleaned.length) {
        const a = cleaned[i];
        const b = i + 1 < cleaned.length ? cleaned[i + 1] : 'X';

        if (a === b) {
            result += a + 'X';
            i++;
        } else {
            result += a + b;
            i += 2;
        }
    }

    // oxirgi juftlikni toʻldirish
    if (result.length % 2 === 1) result += 'X';
    return result;
}

/* -------------------------------------------------
   Shifrlash
   ------------------------------------------------- */
function encrypt() {
    if (!validateInputs()) return;

    matrix.value = generatePlayfairMatrix(key.value);
    const prepared = prepareText(plaintext.value);
    let result = '';

    for (let i = 0; i < prepared.length; i += 2) {
        const [r1, c1] = findPosition(matrix.value, prepared[i]);
        const [r2, c2] = findPosition(matrix.value, prepared[i + 1]);

        let e1 = '', e2 = '';

        if (r1 === r2) {
            // bir qatorda → oʻngga
            e1 = matrix.value[r1][(c1 + 1) % 5];
            e2 = matrix.value[r2][(c2 + 1) % 5];
        } else if (c1 === c2) {
            // bir ustunda → pastga
            e1 = matrix.value[(r1 + 1) % 5][c1];
            e2 = matrix.value[(r2 + 1) % 5][c2];
        } else {
            // toʻrtburchak
            e1 = matrix.value[r1][c2];
            e2 = matrix.value[r2][c1];
        }

        result += e1 + e2;
    }

    ciphertext.value = result;
}

/* -------------------------------------------------
   Deshifrlash
   ------------------------------------------------- */
function decrypt() {
    if (!validateInputs()) return;

    matrix.value = generatePlayfairMatrix(key.value);
    const upperCipher = plaintext.value.toUpperCase().replace(/J/g, 'I').replace(/[^A-Z]/g, '');

    if (upperCipher.length % 2 !== 0) {
        errorMessage.value = 'Shifrlangan matn juft uzunlikda boʻlishi kerak!';
        return;
    }

    let result = '';

    for (let i = 0; i < upperCipher.length; i += 2) {
        const [r1, c1] = findPosition(matrix.value, upperCipher[i]);
        const [r2, c2] = findPosition(matrix.value, upperCipher[i + 1]);

        let d1 = '', d2 = '';

        if (r1 === r2) {
            // bir qatorda → chapga
            d1 = matrix.value[r1][(c1 + 4) % 5];
            d2 = matrix.value[r2][(c2 + 4) % 5];
        } else if (c1 === c2) {
            // bir ustunda → yuqoriga
            d1 = matrix.value[(r1 + 4) % 5][c1];
            d2 = matrix.value[(r2 + 4) % 5][c2];
        } else {
            // toʻrtburchak
            d1 = matrix.value[r1][c2];
            d2 = matrix.value[r2][c1];
        }

        result += d1 + d2;
    }

    ciphertext.value = result;
}

/* -------------------------------------------------
   About boʻlimi
   ------------------------------------------------- */
function toggleAbout() {
    showAbout.value = !showAbout.value;
}
</script>

<template>
    <Head title="Playfair Cipher" />

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
                        class="w-16 h-16 mx-auto text-cyan-600 dark:text-cyan-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16"
                        />
                    </svg>
                </div>
                <h1
                    class="text-5xl font-bold mb-4 tracking-tight
                 bg-gradient-to-r from-cyan-600 to-blue-600
                 dark:from-cyan-400 dark:to-blue-400
                 bg-clip-text text-transparent"
                >
                    Playfair Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    5×5 matritsa asosida juft harflar bilan shifrlash
                </p>
            </div>

            <!-- Form -->
            <div
                class="w-full max-w-2xl mb-10 bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
            >
                <div
                    v-if="errorMessage"
                    class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg"
                >
                    {{ errorMessage }}
                </div>

                <div class="space-y-6">
                    <!-- Matn -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium"
                        >Matn (Plaintext yoki Ciphertext)</label
                        >
                        <textarea
                            v-model="plaintext"
                            placeholder="Shifrlanadigan yoki deshifrlanadigan matnni kiriting..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                     focus:outline-none focus:border-cyan-500 dark:focus:border-cyan-400
                     transition-all duration-300"
                            rows="4"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            J → I, boʻshliqlar olib tashlanadi. Juft boʻlmagan → X qoʻshiladi.
                        </p>
                    </div>

                    <!-- Kalit -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium"
                        >Kalit (Key)</label
                        >
                        <input
                            v-model="key"
                            type="text"
                            placeholder="Kalit soʻzni kiriting (faqat harflar)..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                     bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                     focus:outline-none focus:border-cyan-500 dark:focus:border-cyan-400
                     transition-all duration-300"
                        />
                    </div>

                    <!-- 5×5 Matritsa koʻrsatish -->
                    <div
                        v-if="matrix.length > 0"
                        class="mt-6 p-4 bg-gradient-to-br from-cyan-50 to-blue-50
                   dark:from-cyan-900/20 dark:to-blue-900/20 rounded-xl
                   border border-cyan-200 dark:border-cyan-800"
                    >
                        <h3 class="text-sm font-semibold text-cyan-700 dark:text-cyan-300 mb-3 text-center">
                            5×5 Playfair Matritsasi
                        </h3>
                        <div class="grid grid-cols-5 gap-2">
                            <template v-for="(row, i) in matrix" :key="i">
                                <div
                                    v-for="(cell, j) in row"
                                    :key="j"
                                    class="bg-white dark:bg-gray-800 w-10 h-10 flex items-center justify-center
                         rounded-lg font-bold text-cyan-600 dark:text-cyan-400
                         border border-cyan-300 dark:border-cyan-700"
                                >
                                    {{ cell }}
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tugmalar -->
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-cyan-500 to-blue-600
                     hover:from-cyan-600 hover:to-blue-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Encrypt)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                     bg-gradient-to-r from-blue-500 to-indigo-600
                     hover:from-blue-600 hover:to-indigo-700
                     transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Deshifrlash (Decrypt)
                        </button>
                    </div>

                    <!-- Natija -->
                    <div v-if="ciphertext">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium"
                        >Natija</label
                        >
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
                About Playfair Cipher
            </button>

            <!-- About boʻlimi (collapsible) -->
            <transition name="fade">
                <div
                    v-if="showAbout"
                    class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
                >
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                        Playfair Cipher haqida batafsil
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <strong>Playfair Cipher</strong> – 1854-yilda Charlz Uitston tomonidan
                        ixtiro qilingan, lekin Lord Playfair nomi bilan mashhur boʻlgan birinchi
                        <strong>juft harfli</strong> shifrlash usuli.
                    </p>

                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-cyan-50 dark:bg-cyan-900/20 p-4 rounded-lg border border-cyan-200 dark:border-cyan-800"
                        >
                            <h4 class="font-semibold text-cyan-700 dark:text-cyan-300">5×5 Matritsa</h4>
                            <p class="text-sm mt-1">I/J birlashtirilgan</p>
                        </div>
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800"
                        >
                            <h4 class="font-semibold text-blue-700 dark:text-blue-300">Juft harflar</h4>
                            <p class="text-sm mt-1">HE, LL → HELX</p>
                        </div>
                        <div
                            class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg border border-indigo-200 dark:border-indigo-800"
                        >
                            <h4 class="font-semibold text-indigo-700 dark:text-indigo-300">3 qoida</h4>
                            <p class="text-sm mt-1">Qator, ustun, toʻrtburchak</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">
                        Qanday ishlaydi?
                    </h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-3 mb-6">
                        <li><strong>Matritsa yaratiladi</strong>: Kalit + qolgan harflar (J yoʻq).</li>
                        <li><strong>Juftliklar hosil qilinadi</strong>: Takrorlangan harflar orasiga X, oxiriga X.</li>
                        <li>
                            <strong>3 qoida boʻyicha shifrlanadi</strong>:
                            <ul class="list-disc list-inside ml-6 mt-1 space-y-1 text-sm">
                                <li><strong>Bir qatorda</strong> → oʻngga siljish</li>
                                <li><strong>Bir ustunda</strong> → pastga siljish</li>
                                <li><strong>Toʻrtburchak</strong> → oʻng yuqori va chap pastki</li>
                            </ul>
                        </li>
                    </ol>

                    <div
                        class="bg-gray-100 dark:bg-gray-900 p-5 rounded-xl font-mono text-sm mb-6"
                    >
                        <p><strong>Misol:</strong> Matn: <code>HELLO</code>, Kalit: <code>MONARCHY</code></p>
                        <p class="mt-2">
                            Juftlik: <code>HE LX LO</code> (L takrorlangan → LX)<br />
                            Matritsa: <code>M O N A R<br />C H Y B D<br />E F G I K<br />L P Q S T<br />U V W X Z</code>
                        </p>
                        <p class="mt-2">
                            HE → BM<br />
                            LX → CM<br />
                            LO → UZ
                        </p>
                        <p class="mt-2 font-bold text-green-600 dark:text-green-400">
                            Natija: <code>BMCMUZ</code>
                        </p>
                    </div>

                    <div
                        class="bg-amber-50 dark:bg-amber-900/30 p-5 rounded-xl border border-amber-300 dark:border-amber-700 mb-6"
                    >
                        <h4 class="font-semibold text-amber-800 dark:text-amber-200 mb-2">
                            Afzalliklari
                        </h4>
                        <ul class="text-sm text-amber-700 dark:text-amber-300 space-y-1">
                            <li>Chastota tahliliga chidamli</li>
                            <li>Juft harflar → qiyinroq</li>
                            <li>Oson yodlash</li>
                        </ul>
                    </div>

                    <div
                        class="bg-red-50 dark:bg-red-900/30 p-5 rounded-xl border border-red-300 dark:border-red-700"
                    >
                        <h4 class="font-semibold text-red-800 dark:text-red-200 mb-2">
                            Kamchiliklari
                        </h4>
                        <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                            <li>26×26 = 676 ta juftlik → kichik kalit maydoni</li>
                            <li>Zamonaviy kompyuterlar bilan buziladi</li>
                            <li>Qisqa matnlar zaif</li>
                        </ul>
                    </div>

                    <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                        <div class="bg-cyan-50 dark:bg-cyan-900/20 p-4 rounded-lg">
                            <p class="text-xs text-cyan-700 dark:text-cyan-300">1854</p>
                            <p class="font-semibold">Ixtiro</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <p class="text-xs text-blue-700 dark:text-blue-300">Juft</p>
                            <p class="font-semibold">Harflar</p>
                        </div>
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                            <p class="text-xs text-indigo-700 dark:text-indigo-300">5×5</p>
                            <p class="font-semibold">Matritsa</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <p class="text-xs text-purple-700 dark:text-purple-300">Zaif</p>
                            <p class="font-semibold">Zamonaviy</p>
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
