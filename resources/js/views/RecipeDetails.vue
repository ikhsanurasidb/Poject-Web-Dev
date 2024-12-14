<template>
  <div class="container mx-auto p-6 mt-4 bg-white rounded-lg shadow-md">
    <h1 class="text-4xl font-bold mb-6 text-primary text-center">{{ recipe.title }}</h1>

    <div v-if="loading" class="text-center py-4">
      <p class="text-lg">Loading recipe details...</p>
      <svg class="animate-spin h-5 w-5 mx-auto text-orange-500" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
      </svg>
    </div>

    <div v-else-if="error" class="text-red-500 text-center py-4">
      <p class="text-lg">{{ error }}</p>
    </div>

    <div v-else>
      <div class="mb-4">
        <img :src="recipe.image_url" alt="Recipe Image" class="w-full h-64 object-cover rounded-lg shadow-md" />
      </div>
      <div class="mb-4">
        <h2 class="text-2xl font-semibold">Ingredients</h2>
        <ul class="list-disc pl-5">
          <li v-for="(ingredient, index) in recipe.ingredients" :key="index">
            <span class="font-bold">{{ ingredient.quantity }}</span> {{ ingredient.description }}
          </li>
        </ul>
      </div>
      <div class="mb-4">
        <h2 class="text-2xl font-semibold">Directions</h2>
        <ul class="list-disc pl-5">
        <li v-for="(direction, index) in recipe.directions" :key="index">
            {{ direction.description }}
        </li>
        </ul>
      </div>
      <div class="mb-4">
        <h2 class="text-2xl font-semibold">Durations</h2>
        <p class="text-lg mb-2"><strong>Est:</strong> {{ recipe.duration }} minutes</p>
      </div>
      <div class="mb-4">
        <button @click="createRecipe" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        <button @click="readRecipe(recipe.id)" class="bg-green-500 text-white px-4 py-2 rounded">Read</button>
        <button @click="updateRecipe(recipe.id)" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
        <button @click="deleteRecipe(recipe.id)" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
      </div>
      <router-link to="/" class="text-blue-500 hover:underline">Back to Recipe List</router-link>
    </div>
  </div>
</template>

<script>
import { useRecipeStore } from '@/stores/recipeStore'; // Adjust the path as necessary
import { onMounted, ref } from 'vue';

export default {
  props: {
    id: {
      type: String,
      required: true
    }
  },
  setup(props) {
    const recipeStore = useRecipeStore();
    const recipe = ref({});
    const loading = ref(true);
    const error = ref(null);

    const fetchRecipeDetails = async () => {
      try {
        loading.value = true;
        const response = await recipeStore.fetchRecipeById(props.id); // Call the new method
        recipe.value = response; // Use the response directly
      } catch (err) {
        error.value = 'Failed to load recipe details. Please try again later.';
      } finally {
        loading.value = false;
      }
    };

    onMounted(fetchRecipeDetails);

    return {
      recipe,
      loading,
      error
    };
  }
};
</script>

<style scoped>
.container {
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #333;
}

img {
  border-radius: 8px;
}

.bg-gray-100 {
  background-color: #f7fafc;
}

.shadow-inner {
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>