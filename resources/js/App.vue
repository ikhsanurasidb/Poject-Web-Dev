<template>
  <header class="bg-gray-800 p-4">
    <nav class="container mx-auto flex justify-between items-center">
      <RouterLink :to="{ name: 'home' }" class="text-white text-lg font-bold">Home</RouterLink>

      <div v-if="authStore.user" class="flex items-center space-x-6">
        <p class="text-sm text-slate-300">
          Welcome back, {{ authStore.user.name }}
        </p>
       
        <form @submit.prevent="authStore.logout">
          <Button type="submit" class="text-white hover:text-gray-400">Logout</Button>
        </form>
      </div>

      <div v-else class="space-x-6">
        <RouterLink :to="{ name: 'Create' }" class="text-white hover:text-gray-400">
          <Button>Post a Recipe</Button>
        </RouterLink>
        <RouterLink :to="{ name: 'register' }" class="text-white hover:text-gray-400">
          <Button>Register</Button>
        </RouterLink>
        <RouterLink :to="{ name: 'login' }" class="text-white hover:text-gray-400">
          <Button>Login</Button>
        </RouterLink>
      </div>
    </nav>
  </header>

  <RouterView />
</template>

<script setup>
import Button from "@/components/ui/button/Button.vue";
import { onMounted } from "vue";
import { RouterLink, RouterView } from "vue-router";
import { useAuthStore } from "./stores/auth";

const authStore = useAuthStore();

onMounted(async () => {
  await authStore.getUser ();
});

</script>

<style scoped>
/* Add any additional styles here */
</style>