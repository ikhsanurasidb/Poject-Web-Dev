<script setup>
import { useToast } from '@/components/ui/toast';
import { useRecipeStore } from '@/stores/recipeStore';

const recipeStore = useRecipeStore()
const { toast } = useToast()

const deleteRecipe = async (id) => {
  try {
    await recipeStore.deleteRecipe(id)
    toast({
      title: 'Success',
      description: 'Recipe deleted successfully',
    })
  } catch (error) {
    toast({
      title: 'Error',
      description: error.message || 'Failed to delete recipe',
      variant: 'destructive',
    })
  }
}
</script>