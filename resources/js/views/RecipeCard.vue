<template>
  <div class="max-w-sm rounded overflow-hidden shadow-lg">
    <img class="w-full" :src="recipe.image_url" :alt="recipe.name"> <!-- Updated to use image_url -->
    <div class="px-6 py-4">
      <div class="font-bold text-xl mb-2">{{ recipe.name }}</div>
      <p class="text-gray-700 text-base">{{ recipe.description }}</p>
    </div>
    <div class="px-6 pt-4 pb-2">
      <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" @click="$emit('click', recipe.id)">View Recipe</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    recipe: {
      type: Object,
      required: true,
    },
  },
  emits: ['click'],
};
</script>