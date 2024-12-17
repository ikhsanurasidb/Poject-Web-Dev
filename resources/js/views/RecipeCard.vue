<template>
    <Card class="w-[350px] h-[400px] flex flex-col overflow-hidden group">
      <CardHeader class="p-0 relative">
        <img
          class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
          :src="recipe.image_url"
          :alt="recipe.name"
        />
        <div class="absolute top-2 right-2">
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="secondary" size="icon" class="h-8 w-8 rounded-full bg-white/80 backdrop-blur-sm" @click.stop>
                <MoreVerticalIcon class="h-4 w-4" />
                <span class="sr-only">Open menu</span>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem @click.stop="editRecipe">
                <PencilIcon class="mr-2 h-4 w-4" />
                <span>Edit</span>
              </DropdownMenuItem>
              <DropdownMenuItem @click.stop="confirmDelete">
                <TrashIcon class="mr-2 h-4 w-4" />
                <span>Delete</span>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </CardHeader>
      <CardContent class="flex-grow p-4 flex flex-col justify-between">
        <div>
          <CardTitle class="text-xl mb-2">{{ recipe.name }}</CardTitle>
          <p class="truncate w-full">{{ recipe.description }}</p>
        </div>
        <div class="flex justify-between items-center mt-4">
          <!-- <Badge variant="secondary" class="text-xs">
            {{ recipe.category }}
          </Badge> -->
          <Button variant="outline" size="sm" @click="viewRecipe">
            View Recipe
            <ChevronRightIcon class="ml-2 h-4 w-4" />
          </Button>
        </div>
      </CardContent>
    </Card>
</template>
  
  <script setup>
  import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useToast } from '@/components/ui/toast';
import { ChevronRightIcon, MoreVerticalIcon, PencilIcon, TrashIcon } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
  
  const props = defineProps({
    recipe: {
      type: Object,
      required: true,
    },
  });
  
  const emit = defineEmits(['delete']);
  const router = useRouter();
  const { toast } = useToast();
  
  const viewRecipe = () => {
    router.push({ name: 'recipe', params: { id: props.recipe.id } });
  };
  
  const editRecipe = () => {
    router.push({ name: 'Update', params: { id: props.recipe.id } });
  };
  
  const confirmDelete = () => {
    if (window.confirm(`Are you sure you want to delete "${props.recipe.name}"?`)) {
      console.log('Emitting delete event for recipe:', props.recipe.id);
      emit('delete', props.recipe.id);
      toast({
        title: 'Recipe Deleted',
        description: `"${props.recipe.name}" has been deleted successfully.`,
        variant: 'default',
      });
    }
  };
  </script>