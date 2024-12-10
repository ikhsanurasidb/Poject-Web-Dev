<script setup lang="ts">
</script>

<template>
    <nav>
        <div class="flex gap-16 text-4xl">
            <router-link to="/">Home</router-link>
            <router-link to="/about">About</router-link>
        </div>
    </nav>
    <main>
        <router-view />
    </main>
</template>
