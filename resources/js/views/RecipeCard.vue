<template>
    <Card class="w-[350px] h-[400px] flex flex-col overflow-hidden group">
        <CardHeader class="relative p-0">
            <img
                class="object-cover w-full h-48 transition-transform duration-300 group-hover:scale-105"
                :src="recipe.image_url"
                :alt="recipe.name"
            />
            <div class="absolute top-2 right-2">
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button
                            variant="secondary"
                            size="icon"
                            class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm"
                            @click.stop
                        >
                            <MoreVerticalIcon class="w-4 h-4" />
                            <span class="sr-only">Open menu</span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem @click.stop="editRecipe">
                            <PencilIcon class="w-4 h-4 mr-2" />
                            <span>Edit</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click.stop="openDeleteDialog">
                            <TrashIcon class="w-4 h-4 mr-2" />
                            <span>Delete</span>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </CardHeader>
        <CardContent class="flex flex-col justify-between flex-grow p-4">
            <div>
                <CardTitle class="mb-2 text-xl">{{ recipe.name }}</CardTitle>
                <p class="w-full truncate">{{ recipe.description }}</p>
            </div>
            <div class="flex items-center justify-between mt-4">
                <Button variant="outline" size="sm" @click="viewRecipe">
                    View Recipe
                    <ChevronRightIcon class="w-4 h-4 ml-2" />
                </Button>
            </div>
        </CardContent>
    </Card>

    <Dialog
        :open="isDeleteDialogOpen"
        @update:open="isDeleteDialogOpen = $event"
    >
        <DialogContent>
            <DialogHeader>
                <DialogTitle
                    >Are you sure you want to delete this recipe?</DialogTitle
                >
                <DialogDescription>
                    This action cannot be undone. This will permanently delete
                    "{{ recipe.name }}".
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button @click="closeDeleteDialog" variant="outline"
                    >Cancel</Button
                >
                <Button @click="deleteRecipe" variant="destructive"
                    >Delete</Button
                >
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { useToast } from "@/components/ui/toast";
import {
    ChevronRightIcon,
    MoreVerticalIcon,
    PencilIcon,
    TrashIcon,
} from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";

const props = defineProps({
    recipe: {
        type: Object,
        required: true,
    },
});

const authStore = useAuthStore();
const emit = defineEmits(["delete"]);
const router = useRouter();
const { toast } = useToast();
const isDeleteDialogOpen = ref(false);
const APP_URL = import.meta.env.VITE_APP_URL;

const viewRecipe = () => {
    router.push({ name: "recipe", params: { id: props.recipe.id } });
};

const editRecipe = () => {
    router.push({ name: "Update", params: { id: props.recipe.id } });
};

const openDeleteDialog = () => {
    isDeleteDialogOpen.value = true;
};

const closeDeleteDialog = () => {
    isDeleteDialogOpen.value = false;
};

const deleteRecipe = async () => {
    try {
        await axios.delete(
            `${APP_URL}/api/recipes/${props.recipe.id}`,
            {
                headers: {
                    Authorization: `Bearer ${authStore.token}`,
                },
            }
        );
        closeDeleteDialog();
        emit("delete", props.recipe.id);
        toast({
            title: "Recipe Deleted",
            description: `"${props.recipe.name}" has been deleted successfully.`,
            variant: "default",
        });
        // router.push("/");
    } catch (error) {
        console.error("Error deleting recipe:", error);
        toast({
            title: "Error",
            description: "Failed to delete the recipe. Please try again.",
            variant: "destructive",
        });
    }
};
</script>
