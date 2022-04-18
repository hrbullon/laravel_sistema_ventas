<script setup>
import { ref } from 'vue';

import Header from '@/Layouts/shared/Header.vue';
import Sidebar from '@/Layouts/shared/Sidebar.vue';
import Footer from '@/Layouts/shared/Footer.vue';

</script>

<template>

    <Header />

    <div class="app-body">
        <Sidebar />
        <!-- Contenido Principal -->
        <main class="main">
            <slot />
        </main>
        <!-- /Fin del contenido principal -->
    </div>

    <Footer />
    
</template>
