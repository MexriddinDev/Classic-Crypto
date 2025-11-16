<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    LayoutGrid,
    Shield,
    Info,
    CircleParkingIcon,
    VideoIcon,
    VideoOffIcon,
    YoutubeIcon,
} from 'lucide-vue-next';

// Asosiy menyu bo‘limlari
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Sezar usuli',
        href: '/caesar',
        icon: CircleParkingIcon,
    },
    {
        title: 'Monoalphabetic Shifr',
        href: '/monoalphabetic',
        icon: CircleParkingIcon,
    },
    {
        title: 'Playfair Shifr',
        href: '/playfair',
        icon: CircleParkingIcon,
    },
    {
        title: 'Veginere Shifr',
        href: '/vegenere',
        icon: CircleParkingIcon,
    },
    {
        title: 'Hill Shifr',
        href: '/hillcipher',
        icon: CircleParkingIcon,
    },

    {
        title: 'Beaufort Shifr',
        href: '/beaufort',
        icon: CircleParkingIcon,
    },
    {
        title: 'Autokey Shifr',
        href: '/autokey',
        icon: CircleParkingIcon,
    },

    {
        title: 'Homophonic Shifr',
        href: '/homophonic',
        icon: CircleParkingIcon,
    },

    {
        title: 'Affine Shifr',
        href: '/affine',
        icon: CircleParkingIcon,
    },
    {
        title: 'Atbash Shifr',
        href: '/atbash',
        icon: CircleParkingIcon,
    },
    {
        title: 'Scytale Shifr',
        href: '/scytale',
        icon: CircleParkingIcon,
    },

    {
        title: 'Columnar Shifr',
        href: '/columnar',
        icon: CircleParkingIcon,
    },

    {
        title: 'shiflash videolari',
        href: '/ciphervideos',
        icon: YoutubeIcon,
    },


    {
        title: 'Biz haqimizda',
        href: '/jamoa',
        icon: Info,
    },
];

// Footer bo‘limlari
const footerNavItems: NavItem[] = [
    {
        title: 'Github (Loyihamiz)',
        href: '',
        icon: Shield,
    },
];
</script>

<template>
    <div
        class="flex min-h-screen bg-gradient-to-b from-gray-100 via-gray-50 to-gray-100 text-gray-800 transition-colors duration-500 dark:from-[#0b0b0d] dark:via-[#111113] dark:to-[#0d0d0f] dark:text-gray-200"
    >
        <Sidebar
            collapsible="icon"
            variant="inset"
            class="transition-all duration-500"
        >
            <!-- Header qismi -->
            <SidebarHeader
                class="border-b border-gray-200 dark:border-gray-700"
            >
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" as-child>
                            <Link :href="dashboard()">
                                <div class="flex items-center gap-2">
                                    <!--                                    <AppLogo />-->
                                    <span
                                        class="bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-lg font-bold text-transparent dark:from-indigo-400 dark:to-purple-400"
                                    >
                                        CryptoClassic
                                    </span>
                                </div>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <!-- Asosiy menyu -->
            <SidebarContent class="py-4">
                <NavMain :items="mainNavItems" />
            </SidebarContent>

            <!-- Footer menyu -->
            <SidebarFooter
                class="mt-auto border-t border-gray-200 dark:border-gray-700"
            >
                <NavFooter :items="footerNavItems" />
                <div
                    class="px-3 py-2 text-center text-xs text-gray-500 dark:text-gray-400"
                ></div>
                <NavUser />
            </SidebarFooter>
        </Sidebar>

        <!-- Asosiy kontent -->
        <main
            class="flex-1 bg-white/80 backdrop-blur-md transition-all duration-300 dark:bg-[#0e0e10]/90"
        >
            <slot />
        </main>
    </div>
</template>

<style scoped>
/* Neon effekt uchun */
.sidebar-gradient {
    background: linear-gradient(
        180deg,
        rgba(59, 130, 246, 0.15) 0%,
        rgba(147, 51, 234, 0.15) 100%
    );
}

main {
    transition: background-color 0.4s ease;
}
</style>
