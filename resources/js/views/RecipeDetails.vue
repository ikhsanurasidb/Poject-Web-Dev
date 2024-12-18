<template>
    <div class="container p-6 mx-auto space-y-8">
        <Card v-if="loading">
            <CardHeader>
                <CardTitle>Loading Recipe</CardTitle>
            </CardHeader>
            <CardContent class="flex items-center justify-center p-6">
                <Loader2Icon class="w-8 h-8 animate-spin" />
            </CardContent>
        </Card>

        <Card v-else-if="error">
            <CardHeader>
                <CardTitle class="text-destructive">Error</CardTitle>
            </CardHeader>
            <CardContent>
                <p>{{ error }}</p>
            </CardContent>
        </Card>

        <div v-else class="space-y-8">
            <Card>
                <CardHeader>
                    <CardTitle class="text-3xl font-bold">{{
                        recipe.title
                    }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <img
                        :src="recipe.image_url"
                        :alt="recipe.title"
                        class="object-cover w-full mb-6 rounded-lg shadow-md h-80"
                    />
                    <p class="text-lg">{{ recipe.description }}</p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Ingredients</CardTitle>
                </CardHeader>
                <CardContent>
                    <ul class="pl-5 space-y-2 list-disc">
                        <li
                            v-for="(ingredient, index) in recipe.ingredients"
                            :key="index"
                        >
                            <span class="font-semibold">{{
                                ingredient.quantity
                            }}</span>
                            {{ ingredient.description }}
                        </li>
                    </ul>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Directions</CardTitle>
                </CardHeader>
                <CardContent>
                    <ol class="pl-5 space-y-4 list-decimal">
                        <li
                            v-for="(direction, index) in recipe.directions"
                            :key="index"
                            class="flex flex-col gap-4"
                        >
                            <span>
                                <Badge variant="secondary"
                                    >Step {{ index + 1 }}</Badge
                                >
                                {{ direction.description }}</span
                            >
                            <img
                                v-if="direction.image_url"
                                :src="direction.image_url"
                                :alt="`Step ${index + 1}`"
                                class="object-cover w-full h-auto max-w-md rounded-lg shadow-md"
                            />
                        </li>
                    </ol>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Duration</CardTitle>
                </CardHeader>
                <CardContent>
                    <Badge variant="secondary" class="text-lg">
                        {{ recipe.duration }} minutes
                    </Badge>
                </CardContent>
            </Card>

            <div class="flex items-center justify-between">
                <Button variant="outline" @click="$router.push('/')">
                    <ChevronLeftIcon class="w-4 h-4 mr-2" /> Back to Recipe List
                </Button>
                <!-- <Button @click="toggleFavorite">
          <HeartIcon :class="{'fill-current': recipe.isFavorite}" class="w-4 h-4 mr-2" />
          {{ recipe.isFavorite ? 'Remove from Favorites' : 'Add to Favorites' }}
        </Button> -->
            </div>
        </div>
    </div>
</template>

<script setup>
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { useRecipeStore } from "@/stores/recipeStore";
import { ChevronLeftIcon, Loader2Icon } from "lucide-vue-next";
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const recipeStore = useRecipeStore();

const recipe = ref({});
const loading = ref(true);
const error = ref(null);

const fetchRecipeDetails = async () => {
    try {
        loading.value = true;
        const response = await recipeStore.fetchRecipeById(route.params.id);
        recipe.value = { ...response, isFavorite: false }; // Add isFavorite property
    } catch (err) {
        error.value = "Failed to load recipe details. Please try again later.";
    } finally {
        loading.value = false;
    }
};

const toggleFavorite = () => {
    recipe.value.isFavorite = !recipe.value.isFavorite;
    // Here you would typically call an API to update the favorite status
};

onMounted(fetchRecipeDetails);
</script>
