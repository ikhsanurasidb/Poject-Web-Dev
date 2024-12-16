<template>
    <div class="flex items-center justify-center min-h-screen p-4 bg-orange-50">
        <Card class="w-full max-w-md">
            <CardHeader>
                <CardTitle class="text-2xl font-bold text-orange-800"
                    >Chef's Login</CardTitle
                >
                <CardDescription
                    >Enter your secret ingredients to access the
                    kitchen!</CardDescription
                >
            </CardHeader>
            <CardContent>
                <form @submit="onSubmit" class="space-y-4">
                    <FormField
                        v-slot="{ field }"
                        name="email"
                        :control="form.control"
                    >
                        <FormItem>
                            <FormLabel>Email</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="field"
                                    placeholder="chef@example.com"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField
                        v-slot="{ field }"
                        name="password"
                        :control="form.control"
                    >
                        <FormItem>
                            <FormLabel>Password</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="field"
                                    type="password"
                                    placeholder="Your secret recipe"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <Button
                        class="w-full bg-orange-600 hover:bg-orange-700"
                        type="submit"
                        :disabled="isLoading"
                    >
                        {{ isLoading ? "Preheating..." : "Enter the Kitchen" }}
                    </Button>
                </form>
            </CardContent>
        </Card>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import { useForm } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import * as z from "zod";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { useToast } from "@/components/ui/toast";

const router = useRouter();
const authStore = useAuthStore();
const { toast } = useToast();

const isLoading = ref(false);

const formSchema = z.object({
    email: z.string().email("Please enter a valid email address"),
    password: z.string().min(4, "Password must be at least 4 characters"),
});

const form = useForm({
    validationSchema: toFormValidator(formSchema),
});

const onSubmit = form.handleSubmit(async (values) => {
    isLoading.value = true;
    try {
        const response = await axios.post("http://localhost:8000/api/login", {
            email: values.email,
            password: values.password,
        });

        console.log(response.data.success);

        if (response.data.success) {
            authStore.setToken(response.data.data);
            authStore.setEmail(values.email);
            toast({
                title: "Welcome to the kitchen!",
                description: "You've successfully logged in.",
                variant: "success",
            });
            router.push("/");
        } else {
            throw new Error(response.data.message || "Login failed");
        }
    } catch (error) {
        toast({
            title: "Oops! Something went wrong",
            description: error.message || "Failed to log in. Please try again.",
            variant: "destructive",
        });
    } finally {
        isLoading.value = false;
    }
});
</script>
