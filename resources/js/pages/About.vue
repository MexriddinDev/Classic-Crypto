<template>
    <AppLayout>
        <Head title="Biz haqimizda" />

        <section class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen py-20 px-6">
            <div class="max-w-6xl mx-auto text-center">
                <h1 class="text-5xl font-bold text-gray-800 mb-6">Biz haqimizda</h1>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto mb-12">
                    Biz yosh, ammo tajribali dasturchilar va dizaynerlardan tashkil topgan jamoamiz.
                    Maqsadimiz — innovatsion texnologiyalar orqali zamonaviy, samarali va foydalanuvchiga qulay yechimlar yaratish.
                </p>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10 place-items-center"
            >
                <div
                    v-for="(member, index) in team"
                    :key="index"
                    class="relative group w-72 h-80 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300"
                >
                    <img
                        :src="member.image"
                        :alt="member.name"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    />
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-5"
                    >
                        <h2 class="text-white text-xl font-semibold">{{ member.name }}</h2>
                        <p class="text-gray-200 text-sm">{{ member.role }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-20 text-center">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Bizning qadriyatlar</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Halollik, sifat va jamoaviy ruh — bizning muvaffaqiyat kalitimiz.
                    Har bir loyiha biz uchun nafaqat ish, balki ijodiy jarayondir.
                </p>
            </div>
        </section>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const team = [
    { name: 'Mehriddin Nuriddinov', role: 'Full Stack Developer', image: '/images/about1.jpg' },
    { name: 'Jamshid Karimov', role: 'Frontend Developer', image: '/images/about2.jpg' },
    { name: 'Nodira Qodirova', role: 'UI/UX Dizayner', image: '/images/about3.jpg' },
    { name: 'Dilshod Rahmonov', role: 'Backend Developer', image: '/images/about4.jpg' },
    { name: 'Gulnoza Yuldosheva', role: 'Project Manager', image: '/images/about5.jpg' },
    { name: 'Sherzod Xasanov', role: 'QA Engineer', image: '/images/about6.jpg' },
    { name: 'Zuhra Abdurahmonova', role: 'Marketing Specialist', image: '/images/about7.jpg' },
]
</script>

<style scoped>
section {
    background: radial-gradient(circle at top, #f9fafb 0%, #e5e7eb 100%);
}
</style>
