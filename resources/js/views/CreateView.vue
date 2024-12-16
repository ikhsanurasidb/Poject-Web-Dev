<script setup>
import { ref } from "vue";
import { Button } from "@/components/ui/button/Button.vue";
import { Input } from "@/components/ui/input/Input.vue";
import { Label } from "@/components/ui/label/Label.vue";
// import Label from "@/components/ui/label/Label.vue";
import { Select } from "@/components/ui/select/Select.vue";
import { Switch } from "@/components/ui/switch/Switch.vue";
import { Textarea } from "@/components/ui/textarea/Textarea.vue";
import {
    ImageIcon,
    InfoIcon,
    PlusIcon,
    TrashIcon,
    DotsVerticalIcon,
} from "lucide-vue-next";

const previewImage = ref(null);
const recipe = ref({
    name: "",
    servings: "",
    duration: 30,
    ingredients: [],
    directions: [],
});

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const addIngredient = () => {
    recipe.value.ingredients.push({
        quantity: "",
        unit: "pcs",
        name: "",
        hasSpecificSize: false,
    });
};

const removeIngredient = (index) => {
    recipe.value.ingredients.splice(index, 1);
};

const addDirection = () => {
    recipe.value.directions.push({
        text: "",
    });
};

const removeDirection = (index) => {
    recipe.value.directions.splice(index, 1);
};

const showIngredientInfo = () => {
    // Implement ingredient info modal/tooltip
    console.log("Show ingredient info");
};

const showDirectionInfo = () => {
    // Implement direction info modal/tooltip
    console.log("Show direction info");
};

const handlePublish = () => {
    // Implement publish logic
    console.log("Publishing recipe:", recipe.value);
};
</script>

<template>
    <div class="container mx-auto p-6 max-w-6xl">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-semibold">Create new recipe</h1>
            <Button @click="handlePublish" variant="default">
                Publish recipe
            </Button>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Recipe General Information -->
            <div class="space-y-6">
                <h2 class="text-lg font-medium text-gray-700">
                    Recipe General Information
                </h2>

                <!-- Photo Upload -->
                <div
                    class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"
                >
                    <div class="space-y-2">
                        <div class="flex justify-center">
                            <ImageIcon
                                v-if="!previewImage"
                                class="h-12 w-12 text-gray-400"
                            />
                            <img
                                v-else
                                :src="previewImage"
                                class="max-h-48 rounded-lg"
                                alt="Recipe preview"
                            />
                        </div>
                        <div class="flex justify-center">
                            <input
                                type="file"
                                ref="fileInput"
                                class="hidden"
                                accept="image/png,image/jpeg"
                                @change="handleImageUpload"
                            />
                            <Button
                                variant="outline"
                                @click="$refs.fileInput.click()"
                            >
                                Upload Photo
                            </Button>
                        </div>
                        <p class="text-sm text-gray-500">
                            PNG or JPEG (max. 10MB)
                        </p>
                    </div>
                </div>

                <!-- Recipe Name -->
                <div class="space-y-2">
                    <Label for="recipe-name">Recipe name</Label>
                    <Input
                        id="recipe-name"
                        v-model="recipe.name"
                        placeholder="eg: Savory Stuffed Bell Peppers"
                    />
                </div>

                <!-- Number of Servings -->
                <div class="space-y-2">
                    <Label for="servings">Number of serving</Label>
                    <div class="flex items-center gap-2">
                        <Input
                            id="servings"
                            v-model="recipe.servings"
                            placeholder="eg: 4 or 3-5"
                        />
                        <span class="text-gray-500">person</span>
                    </div>
                </div>

                <!-- Cook Duration -->
                <div class="space-y-2">
                    <Label for="duration">Cook duration</Label>
                    <div class="flex items-center gap-2">
                        <Input
                            id="duration"
                            v-model="recipe.duration"
                            type="number"
                        />
                        <span class="text-gray-500">minute</span>
                    </div>
                </div>
            </div>

            <!-- Recipe Detail -->
            <div class="space-y-6">
                <h2 class="text-lg font-medium text-gray-700">Recipe Detail</h2>

                <!-- Ingredients -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium">Ingredients</h3>
                        <Button
                            variant="ghost"
                            size="sm"
                            @click="showIngredientInfo"
                        >
                            <InfoIcon class="h-4 w-4" />
                        </Button>
                    </div>

                    <div
                        v-for="(ingredient, index) in recipe.ingredients"
                        :key="index"
                        class="space-y-4"
                    >
                        <div class="flex items-start gap-2">
                            <div class="flex-none">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6"
                                >
                                    <DotsVerticalIcon
                                        class="h-4 w-4 text-gray-400"
                                    />
                                </span>
                            </div>

                            <div class="flex-1 grid grid-cols-12 gap-2">
                                <Input
                                    v-model="ingredient.quantity"
                                    class="col-span-2"
                                    type="number"
                                />
                                <Select
                                    v-model="ingredient.unit"
                                    class="col-span-2"
                                >
                                    <option value="pcs">pcs</option>
                                    <option value="cup">cup</option>
                                    <option value="oz">oz</option>
                                    <option value="tbsp">tbsp</option>
                                    <option value="tsp">tsp</option>
                                </Select>
                                <Input
                                    v-model="ingredient.name"
                                    class="col-span-7"
                                    placeholder="eg: Large bell peppers (any color)"
                                />
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="col-span-1"
                                    @click="removeIngredient(index)"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>

                        <div class="ml-8">
                            <div class="flex items-center gap-2">
                                <Switch v-model="ingredient.hasSpecificSize" />
                                <span class="text-sm text-gray-600"
                                    >Detailed specific size</span
                                >
                            </div>
                        </div>
                    </div>

                    <Button
                        variant="outline"
                        class="w-full"
                        @click="addIngredient"
                    >
                        <PlusIcon class="h-4 w-4 mr-2" />
                        Add ingredients
                    </Button>
                </div>

                <!-- Directions -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium">Directions</h3>
                        <Button
                            variant="ghost"
                            size="sm"
                            @click="showDirectionInfo"
                        >
                            <InfoIcon class="h-4 w-4" />
                        </Button>
                    </div>

                    <div
                        v-for="(direction, index) in recipe.directions"
                        :key="index"
                        class="space-y-4"
                    >
                        <div class="flex items-start gap-2">
                            <div class="flex-none">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full text-sm"
                                >
                                    {{ String(index + 1).padStart(2, "0") }}
                                </span>
                            </div>

                            <div class="flex-1 flex gap-2">
                                <Textarea
                                    v-model="direction.text"
                                    :placeholder="`eg: Step ${
                                        index + 1
                                    } instructions...`"
                                    rows="3"
                                    class="flex-1"
                                />
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    @click="removeDirection(index)"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>

                    <Button
                        variant="outline"
                        class="w-full"
                        @click="addDirection"
                    >
                        <PlusIcon class="h-4 w-4 mr-2" />
                        Add directions
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
