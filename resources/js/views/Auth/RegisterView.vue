<template>
    <div class="flex items-center justify-center min-h-screen p-4 bg-orange-50">
        <Card class="w-full max-w-md">
            <CardHeader>
                <CardTitle class="text-2xl font-bold text-orange-800"
                    >Chef's Registration</CardTitle
                >
                <CardDescription
                    >Join our culinary community and start cooking up a
                    storm!</CardDescription
                >
            </CardHeader>
            <CardContent>
                <form @submit="onSubmit" class="space-y-4">
                    <FormField
                        v-slot="{ field }"
                        name="name"
                        :control="form.control"
                    >
                        <FormItem>
                            <FormLabel>Name</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="field"
                                    placeholder="Gordon Ramsay"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
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
                                    type="email"
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
                    <FormField
                        v-slot="{ field }"
                        name="password_confirmation"
                        :control="form.control"
                    >
                        <FormItem>
                            <FormLabel>Confirm Password</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="field"
                                    type="password"
                                    placeholder="Repeat your secret recipe"
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
                        {{
                            isLoading
                                ? "Preparing your kitchen..."
                                : "Start Cooking!"
                        }}
                    </Button>
                </form>
            </CardContent>
            <CardFooter>
                <p class="w-full text-sm text-center">
                    Already have an account?
                    <router-link
                        to="/login"
                        class="text-orange-600 hover:underline"
                        >Login here</router-link
                    >
                </p>
            </CardFooter>
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
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import {
    Form,
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

const formSchema = z
    .object({
        name: z.string().min(2, "Name must be at least 2 characters"),
        email: z.string().email("Please enter a valid email address"),
        password: z.string().min(6, "Password must be at least 6 characters"),
        password_confirmation: z.string(),
    })
    .refine((data) => data.password === data.password_confirmation, {
        message: "Passwords don't match",
        path: ["password_confirmation"],
    });

const form = useForm({
    validationSchema: toFormValidator(formSchema),
});

const onSubmit = form.handleSubmit(async (values) => {
    console.log("Form submitted with values:", values);
    isLoading.value = true;
    try {
        console.log("Sending registration request...");
        const response = await axios.post(
            "http://localhost:8000/api/register",
            {
                name: values.name,
                email: values.email,
                password: values.password,
                password_confirmation: values.password_confirmation,
            }
        );
        console.log("Registration response:", response.data);

        if (response.data.success) {
            console.log("Registration successful, setting token...");
            authStore.setToken(response.data.data.token);
            authStore.setName(response.data.data.name);
            console.log("Showing success toast...");
            toast({
                title: "Welcome to the kitchen!",
                description: `Congratulations, ${response.data.data.name}! You've successfully registered.`,
                variant: "success",
            });
            console.log("Navigating to dashboard...");
            await router.push("/");
            console.log("Navigation complete");
        } else {
            throw new Error(response.data.message || "Registration failed");
        }
    } catch (error) {
        console.error("Error during registration:", error);
        toast({
            title: "Oops! Something went wrong",
            description:
                error.response?.data?.message ||
                "Failed to register. Please try again.",
            variant: "destructive",
        });
    } finally {
        isLoading.value = false;
    }
});
</script>
