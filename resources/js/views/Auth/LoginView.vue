<template>
  <main class="flex flex-col items-center justify-start min-h-screen bg-gray-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
      <img src="@/components/ui/food-background.png" alt="Food Background" class="w-full h-full object-cover" />
    </div>

    <div class="bg-white shadow-lg rounded-lg p-12 w-full max-w-lg mt-32 z-10">
      <h1 class="text-4xl font-bold text-center mb-8">Welcome Back!</h1>
      <p class="text-center text-gray-600 mb-6">Please login to your account.</p>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <div>
          <Input
            type="text"
            placeholder="Email"
            v-model="formData.email"
            :error="errors.email ? errors.email[0] : ''"
            aria-label="Email"
          />
        </div>

        <div>
          <Input
            type="password"
            placeholder="Password"
            v-model="formData.password"
            :error="errors.password ? errors.password[0] : ''"
            aria-label="Password"
          />
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white hover:bg-blue-700 transition duration-200 py-3 text-lg">Login</button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">Don't have an account? <router-link to="/register" class="text-blue-500 hover:underline">Register</router-link></p>
      </div>
    </div>
  </main>
</template>

<script setup>
import Input from "@/components/ui/Input.vue"; // Import the Input component
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
import { onMounted, reactive } from "vue";

const authStore = useAuthStore();
const { errors } = storeToRefs(authStore);

const formData = reactive({
  email: "",
  password: "",
});

// Clear errors on component mount
onMounted(() => {
  errors.value = {};
});

// Handle login submission
const handleLogin = async () => {
  await authStore.login(formData);
  // Optionally, you can handle additional logic after login
};
</script>

<style scoped>
.food-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
  opacity: 0.1;
}
</style>