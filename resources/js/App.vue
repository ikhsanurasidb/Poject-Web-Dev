<template>
  <header class="p-4 bg-gray-800">
      <nav class="container flex items-center justify-between mx-auto">
          <RouterLink
              :to="{ name: 'home' }"
              class="text-lg font-bold text-white"
              >Home</RouterLink
          >

          <div v-if="authStore.email" class="flex items-center space-x-6">
              <p class="text-sm text-slate-300">
                  Welcome back, {{ authStore.name }}
              </p>
              <RouterLink
                  :to="{ name: 'Create' }"
                  class="text-white hover:text-gray-400"
              >
                  <Button>Post a Recipe</Button>
              </RouterLink>
              <form @submit.prevent="logout">
                  <Button type="submit" class="text-white hover:text-gray-400"
                      >Logout</Button
                  >
              </form>
          </div>

          <div v-else class="space-x-6">
              <RouterLink
                  :to="{ name: 'register' }"
                  class="text-white hover:text-gray-400"
              >
                  <Button>Register</Button>
              </RouterLink>
              <RouterLink
                  :to="{ name: 'login' }"
                  class="text-white hover:text-gray-400"
              >
                  <Button>Login</Button>
              </RouterLink>
          </div>
      </nav>
  </header>

  <RouterView />
  <Toaster />
</template>

<script setup>
import Button from "@/components/ui/button/Button.vue";
import { Toaster } from "@/components/ui/toast";
import axios from "axios";
import { RouterLink, RouterView, useRouter } from "vue-router";
import { useAuthStore } from "./stores/auth";

const router = useRouter();
const authStore = useAuthStore();
const APP_URL = import.meta.env.VITE_APP_URL;

const logout = async () => {
  try {
      await axios.post(
          `${APP_URL}/api/logout`,
          {},
          {
              headers: {
                  Authorization: `Bearer ${authStore.token}`,
              },
          }
      );

      console.log("Logged out successfully");
      authStore.clearAuth();
      localStorage.removeItem("token");

      router.push({ name: "login" });
  } catch (error) {
      console.error("Error during logout:", error);
  }
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
