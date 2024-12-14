<template>
  <main class="flex flex-col items-center justify-start min-h-screen bg-gray-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
      <img src="@/components/ui/food-background.png" alt="Food Background" class="w-full h-full object-cover" />
    </div>

    <div class="bg-white shadow-lg rounded-lg p-12 w-full max-w-lg mt-32 z-10">
      <h1 class="text-4xl font-bold text-center mb-8">Create an Account</h1>
      <p class="text-center text-gray-600 mb-6">Please fill in the details to register.</p>

      <form @submit.prevent="handleRegister" class="space-y-6">
        <div>
          <Input
            type="text"
            placeholder="Name"
            v-model="formData.name"
            :error="errors.name ? errors.name[0] : ''"
            aria-label="Name"
          />
        </div>

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

        <div>
          <Input
            type="password"
            placeholder="Confirm Password"
            v-model="formData.password_confirmation"
            :error="errors.password_confirmation ? errors.password_confirmation[0] : ''"
            aria-label="Confirm Password"
          />
        </div>

        <button type="submit " class="w-full bg-blue-600 text-white hover:bg-blue-700 transition duration-200 py-3 text-lg">Register</button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">Already have an account? <router-link to="/login" class="text-blue-500 hover:underline">Login</router-link></p>
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
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

// Clear errors on component mount
onMounted(() => {
  errors.value = {};
});

// Handle registration submission
const handleRegister = async () => {
  await authStore.register(formData);
  // Optionally, you can handle additional logic after registration
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