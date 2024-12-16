<template>
  <div class="container mx-auto p-6 mt-4 bg-white rounded-lg shadow-md">
    <h1 class="text-4xl font-bold mb-6 text-primary text-center">Recipe Book</h1>
    
    <div class="flex justify-center mb-6">
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Search recipes"
        class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4 py-2 pl-4 text-sm text-gray-700 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-orange-500 transition duration-300"
      />
      <button
        class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-r-md transition duration-300"
        @click="searchRecipes"
      >
        Search
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="recipeStore.loading" class="text-center py-4">
      <p class="text-lg">Loading recipes...</p>
      <svg class="animate-spin h-5 w-5 mx-auto text-orange-500" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
      </svg>
    </div>

    <!-- Error State -->
    <div v-else-if="recipeStore.error" class="text-red-500 text-center py-4">
      <p class="text-lg">{{ recipeStore.error }}</p>
    </div>

    <!-- Recipes Grid -->
    <div v-else>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <RecipeCard
          v-for="recipe in recipeStore.recipes"
          :key="recipe.id"
          :recipe="recipe"
          @click="showRecipeDetails(recipe.id)"
        >
          <div class="relative">
            <button @click.stop="toggleOptions(recipe.id)" class="absolute top-2 right-2">
              <svg class="w-6 h-6 text-gray-600 hover:text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v3m0-18v3m0 0h3m-3 0H9" />
              </svg>
            </button>
            <div v-if="optionsVisible[recipe.id]" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
              <ul class="py-1">
                <li @click="editRecipe(recipe.id)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">Edit</li>
                <li @click="deleteRecipe(recipe.id)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">Delete</li>
              </ul>
            </div>
          </div>
        </RecipeCard>
      </div>

      <!-- Pagination Controls -->
      <div class="flex justify-between items-center mt-6">
        <button
          @click="changePage('prev')"
          :disabled="currentPage === 1"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300 disabled:opacity-50"
        >
          Previous
        </button>
        <span class="text-lg font-semibold">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
          @click="changePage('next')"
          :disabled="currentPage === totalPages"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300 disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { useRecipeStore } from '@/stores/recipeStore';
import { onMounted, ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import RecipeCard from './RecipeCard.vue';

export default {
  components: {
    RecipeCard
  },

  setup() {
    const recipeStore = useRecipeStore();
    const router = useRouter();
    const searchQuery = ref('');
    const currentPage = ref(1);
    const totalPages = ref(0);
    const optionsVisible = ref({});

    const fetchRecipes = async () => {
      const response = await recipeStore.fetchRecipes(currentPage.value, searchQuery.value);
      if (response) {
        recipeStore.recipes = response.data; // Assuming your API returns the recipes in `data`
        totalPages.value = response.meta.last_page; // Assuming your API returns pagination metadata
      }
    };

    const searchRecipes = () => {
      currentPage.value = 1; // Reset to page 1 on new search
      fetchRecipes();
    };

    const changePage = (direction) => {
      if (direction === 'next' && currentPage.value < totalPages.value) {
        currentPage.value++;
      } else if (direction === 'prev' && currentPage.value > 1) {
        currentPage.value--;
      }
      fetchRecipes();
    };

    const showRecipeDetails = (id) => {
      // Navigate to the recipe details page using the recipe ID
      router.push({ name: 'recipe', params: { id: id.toString() } });
    };

    const toggleOptions = (id) => {
      optionsVisible.value[id] = !optionsVisible.value[id];
    };

    const editRecipe = (id) => {
      // Implement edit recipe functionality
      console.log('Edit recipe', id);
    };

    const deleteRecipe = (id) => {
      // Implement delete recipe functionality
      console.log('Delete recipe', id);
    };

    onMounted(fetchRecipes);

    // Watch for changes in searchQuery or currentPage to fetch recipes
    watch([searchQuery, currentPage], () => {
      fetchRecipes();
    });

    return {
      recipeStore,
      searchQuery,
      currentPage,
      totalPages,
      optionsVisible,
      showRecipeDetails,
      changePage,
      searchRecipes,
      toggleOptions,
      editRecipe,
      deleteRecipe
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

input {
  transition: border-color 0.3s;
}

input:focus {
  border-color: #ff7f50;
}

button {
  transition: background-color 0.3s, transform 0.2s;
}

button:hover {
  transform: scale(1.05);
}
</style>