<template>
    <div class="w-full h-full flex justify-center items-center">
        <div class="max-w-xl py-6 px-8 bg-white rounded shadow-xl w-[350px]">
            <form @submit.prevent="submit">
                <text-input v-model="form.username" :error="form.errors.username" class="w-full" label="Username *" />
                <text-input v-model="form.password" :error="form.errors.password" class="w-full mt-4" type="password" label="Password *" />
                <div>
                    <Link href="#"
                        class="text-xs font-thin text-gray-800 hover:underline mt-2 inline-block hover:text-indigo-600">
                    Forget Password
                    </Link>
                </div>
                <button type="submit"
                    class="cursor-pointer py-2 px-4 block mt-6 bg-indigo-500 text-white font-bold w-full text-center rounded">Login
                </button>
                <div class="flex justify-center items-center mt-2 text-sm">
                    <span class="text-sm font-thin text-gray-800 mt-2 inline-block">Don't have
                        account?</span>
                    <Link href="/signup"
                        class="text-xs font-thin hover:underline mt-2 inline-block text-indigo-600 ml-1">
                    Sign up
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import TextInput from '@/Shared/UI/TextInput.vue'


const form = useForm({
    username: '',
    password: '',
});

const submit = () => {
    if (!form.username.trim().length){
        form.errors.username = 'Invalid username';
        return;
    }

    // Ensure the passwords match before submitting
    if (!form.password.trim().length){
        form.errors.password = 'Invalid password';
        return;
    }

    // Send the registration data to the backend
    form.post('/login', {
        onSuccess: () => {
            // Redirect after successful registration
            console.log('Registration successful');
            // You can redirect to the login page or dashboard
            // Inertia.visit('/dashboard') or Inertia.visit('/login')
        },
        onError: () => {
            console.log('Registration failed');
        },
    });
};
</script>
