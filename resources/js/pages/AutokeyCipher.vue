<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Autokey Cipher' },
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

function generateAutokey(plain: string, initialKey: string): string {
    const cleanPlain = plain.toUpperCase().replace(/[^A-Z]/g, '');
    let fullKey = initialKey.toUpperCase();

    // Kalitni matn uzunligiga yetkazamiz, lekin faqat harflardan keyin
    let plainIndex = 0;
    while (fullKey.length < cleanPlain.length) {
        if (plainIndex < cleanPlain.length) {
            fullKey += cleanPlain[plainIndex];
            plainIndex++;
        } else {
            break;
        }
    }

    return fullKey;
}

function encrypt() {
    if (!validateInputs()) return;

    const upperPlain = plaintext.value.toUpperCase();
    const initialKey = key.value.toUpperCase();
    const fullKey = generateAutokey(upperPlain, initialKey);

    let result = '';
    let keyIndex = 0;

    for (let i = 0; i < upperPlain.length; i++) {
        const char = upperPlain[i];
        if (char >= 'A' && char <= 'Z') {
            const p = char.charCodeAt(0) - 65;
            const k = fullKey[keyIndex % fullKey.length].charCodeAt(0) - 65;
            const encrypted = (p + k) % 26;
            result += String.fromCharCode(encrypted + 65);
            keyIndex++;
        } else {
            result += char;
        }
    }

    ciphertext.value = result;
}

function decrypt() {
    if (!validateInputs()) return;

    const upperCipher = plaintext.value.toUpperCase(); // Bu yerda ciphertext
    const initialKey = key.value.toUpperCase();

    let result = '';
    let fullKey = initialKey;
    let keyIndex = 0;

    for (let i = 0; i < upperCipher.length; i++) {
        const char = upperCipher[i];
        if (char >= 'A' && char <= 'Z') {
            const c = char.charCodeAt(0) - 65;
            const k = fullKey[keyIndex % fullKey.length].charCodeAt(0) - 65;
            const decrypted = (c - k + 26) % 26;
            const plainChar = String.fromCharCode(decrypted + 65);
            result += plainChar;

            // Keyni yangilaymiz: key + plaintext (hozircha faqat shifrdan chiqqan harf)
            fullKey += plainChar;
            keyIndex++;
        } else {
            result += char;
        }
    }

    ciphertext.value = result;
}

function toggleAbout() {
    showAbout.value = !showAbout.value;
}
</script>

<template>
    <Head title="Autokey Cipher" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen flex flex-col items-center justify-start py-12 px-6
                bg-gradient-to-br from-gray-50 via-white to-gray-100
                dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-950
                rounded-2xl shadow-xl transition-colors duration-300">

            <!-- Header Section -->
            <div class="text-center mb-12 max-w-3xl">
                <div class="mb-4 inline-block">
                    <svg class="w-16 h-16 mx-auto text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-4 tracking-tight
                       bg-gradient-to-r from-sky-600 to-teal-600
                       dark:from-sky-400 dark:to-teal-400
                       bg-clip-text text-transparent">
                    Autokey Cipher
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium mb-2">
                    Kalit sifatida ochiq matndan bir qismi olinadigan usul
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
                               focus:outline-none focus:border-sky-500 dark:focus:border-sky-400
                               transition-all duration-300"
                            rows="4"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">Boshlang'ich Kalit (Initial Key)</label>
                        <input
                            v-model="key"
                            type="text"
                            placeholder="Boshlang'ich kalit so'zni kiriting (faqat harflar)..."
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                               focus:outline-none focus:border-sky-500 dark:focus:border-sky-400
                               transition-all duration-300"
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Key matn uzunligidan qisqaroq bo'lishi kerak</p>
                    </div>
                    <div class="flex space-x-4">
                        <button
                            @click="encrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                               bg-gradient-to-r from-sky-500 to-teal-600
                               hover:from-sky-600 hover:to-teal-700
                               transition-all duration-300 shadow-md hover:shadow-lg"
                        >
                            Shifrlash (Encrypt)
                        </button>
                        <button
                            @click="decrypt"
                            class="flex-1 px-6 py-3 rounded-lg font-medium text-white
                               bg-gradient-to-r from-indigo-500 to-purple-600
                               hover:from-indigo-600 hover:to-purple-700
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
                About Autokey Cipher
            </button>

            <!-- About Section (Collapsible) -->
            <transition name="fade">
                <div v-if="showAbout" class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Autokey Cipher haqida batafsil</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Autokey Cipher — Vigenère cipherning <strong>avtomatik kalitli</strong> versiyasi. U boshlang'ich kalit (primer key) bilan boshlanadi, keyin esa <strong>ochiq matnning o'zi kalit sifatida ishlatiladi</strong>.
                    </p>

                    <div class="bg-gradient-to-br from-sky-50 to-teal-50 dark:from-sky-900/20 dark:to-teal-900/20 p-6 rounded-xl border border-sky-200 dark:border-sky-800 mb-6">
                        <h3 class="font-semibold text-sky-700 dark:text-sky-300 mb-3">Kalit qanday shakllanadi?</h3>
                        <p class="text-sm mb-2">
                            <code class="bg-white dark:bg-gray-900 px-2 py-1 rounded">Boshlang'ich kalit + Ochiq matn</code>
                        </p>
                        <p class="text-xs">
                            Masalan: Kalit = <code>KEY</code>, Matn = <code>HELLO</code><br>
                            To'liq kalit: <code>KEYHE</code>
                        </p>
                    </div>

                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Qanday shifrlaydi?</h3>
                    <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-2 mb-4">
                        <li>Boshlang'ich kalit olinadi (masalan, <code>KEY</code>).</li>
                        <li>Kalit matn uzunligiga yetguncha <strong>ochiq matn bilan kengaytiriladi</strong>.</li>
                        <li>Har bir harf Vigenère jadvali bo'yicha shifrlanadi: <code>C = (P + K) mod 26</code>.</li>
                    </ol>

                    <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg font-mono text-sm mb-4">
                        <p><strong>Misol:</strong> Matn: <code>HELLO</code>, Kalit: <code>KEY</code></p>
                        <p class="mt-2">
                            To'liq kalit: <code>K E Y H E</code><br>
                            H + K = 7 + 10 = 17 → <code>R</code><br>
                            E + E = 4 + 4 = 8 → <code>I</code><br>
                            L + Y = 11 + 24 = 35 → 9 → <code>J</code><br>
                            L + H = 11 + 7 = 18 → <code>S</code><br>
                            O + E = 14 + 4 = 18 → <code>S</code>
                        </p>
                        <p class="mt-2 font-bold text-green-600 dark:text-green-400">Natija: <code>RIJSS</code></p>
                    </div>

                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Qanday deshifrlaydi?</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Deshifrlashda ham xuddi shu jarayon, lekin kalit <strong>shifrdan chiqqan harflar bilan kengayadi</strong>.<br>
                        <code>P = (C - K) mod 26</code>
                    </p>

                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                            <h4 class="font-semibold text-green-700 dark:text-green-300">Afzalligi</h4>
                            <p class="text-sm mt-1">Kalit uzoqroq → chastota tahliliga chidamliroq</p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-200 dark:border-red-800">
                            <h4 class="font-semibold text-red-700 dark:text-red-300">Kamchiligi</h4>
                            <p class="text-sm mt-1">Agar birinchi harf noto'g'ri taxmin qilinsa — hamma narsa buziladi</p>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-4 rounded-lg border border-amber-300 dark:border-amber-700">
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            <strong>Diqqat:</strong> Autokey cipher Vigenère'dan xavfsizroq, lekin "known plaintext" hujumi bilan osongina buziladi.
                        </p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                        <div class="bg-sky-50 dark:bg-sky-900/20 p-3 rounded-lg">
                            <p class="text-xs text-sky-700 dark:text-sky-300">16-asr</p>
                            <p class="font-semibold">Ixtiro qilingan</p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-900/20 p-3 rounded-lg">
                            <p class="text-xs text-teal-700 dark:text-teal-300">Dinamik kalit</p>
                            <p class="font-semibold">Xususiyat</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg">
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
