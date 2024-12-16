import { defineStore } from 'pinia';

export const useRecipeStore = defineStore('recipe', {
  state: () => ({
    recipes: [],
    loading: false,
    error: null,
    recipeDetails: null, // To store the details of a single recipe
  }),
  actions: {
    async fetchRecipes(page, search) {
      this.loading = true;
      this.error = null;
      try {
        const response = await fetch(`http://localhost:8000/api/recipes?page=${page}&search=${search}`);
        
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const data = await response.json();
        this.recipes = data.data; // Adjust based on your API response structure
        return data; // Return the full response for further processing
      } catch (error) {
        this.error = error.message;
        console.error('Error fetching recipes:', error);
      } finally {
        this.loading = false;
      }
    },

    async fetchRecipeById(id) {
      this.loading = true;
      this.error = null;
      try {
        const response = await fetch(`http://localhost:8000/api/recipes/${id}`);
        
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const data = await response.json();
        this.recipeDetails = data; // Store the recipe details
        return data; // Return the recipe details for further processing
      } catch (error) {
        this.error = error.message;
        console.error('Error fetching recipe details:', error);
      } finally {
        this.loading = false;
      }
    },
  },
});