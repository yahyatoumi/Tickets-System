<template>
    <div class="w-full h-full flex justify-center items-center">
        <div class="max-w-xl py-6 px-8 bg-white rounded shadow-xl w-[350px]">
            <h1>{{ test }}</h1>
            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label for="name" class="block text-gray-800 font-bold">Username:</label>
                    <input v-model="form.username" type="text" name="name" id="name" placeholder="username"
                        class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" />
                    <span v-if="form.errors.username" class="text-red-500 text-xs">{{ form.errors.username }}</span>
                </div>
                <div class="mb-3">
                    <label for="password" class="block text-gray-800 font-bold">Password:</label>
                    <input v-model="form.password" type="password" name="password" id="password" placeholder="password"
                        class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" />
                    <span v-if="form.errors.password" class="text-red-500 text-xs">{{ form.errors.password }}</span>
                </div>
                <div class="mb-3">
                    <label for="password" class="block text-gray-800 font-bold">Confirm password:</label>
                    <input v-model="form.password_confirmation" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password"
                        class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" />
                    <span v-if="form.errors.password_confirmation" class="text-red-500 text-xs">{{ form.errors.password_confirmation }}</span>
                </div>
                <button type="submit"
                    class="cursor-pointer py-2 px-4 block mt-6 bg-indigo-500 text-white font-bold w-full text-center rounded">
                    Register
                </button>
                <div class="flex justify-center items-center mt-2 text-sm">
                    <span class="text-sm font-thin text-gray-800 mt-2 inline-block">Already have an account?</span>
                    <Link href="/login"
                        class="text-xs font-thin hover:underline mt-2 inline-block text-indigo-600 ml-1">
                        Login
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const form = useForm({
    username: '',
    password: '',
    password_confirmation: ''
});

const submit = () => {
    if (!form.username.trim().length){
        form.errors.username = 'Invalid username';
        return;
    }

    // Ensure the passwords match before submitting
    if (form.password !== form.password_confirmation) {
        form.errors.password_confirmation = 'Passwords do not match';
        return;
    }

    // Send the registration data to the backend
    form.post('/users/create', {
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

defineProps({
  test: String,
});
</script>
