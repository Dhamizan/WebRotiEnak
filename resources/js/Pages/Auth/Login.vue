<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
      <div class="rounded-lg shadow-lg w-full max-w-4xl flex flex-col md:flex-row overflow-hidden bg-white">
        <!-- Form Login -->
        <div class="md:w-1/2 w-full flex items-center justify-center p-6 md:p-10">
          <form @submit.prevent="submitLogin" class="w-full">
            <h1 class="text-2xl md:text-3xl font-bold text-black text-center">Welcome Back</h1>
            <p class="text-gray-600 text-sm text-center">Please enter your account</p>
  
            <!-- Username -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700">Username</label>
              <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <UserIcon class="h-5 w-5" />
                </span>
                <input
                  v-model="form.email"
                  type="text"
                  placeholder="Enter Your Username . . ."
                  class="w-full pl-10 p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  required
                />
              </div>
            </div>
  
            <!-- Password -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700">Password</label>
              <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <LockClosedIcon class="h-5 w-5" />
                </span>
                <input
                  :type="showPassword ? 'text' : 'password'"
                  v-model="form.kata_sandi"
                  placeholder="Enter Your Password . . ."
                  class="w-full pl-10 pr-10 p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  required
                />
                <span @click="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 cursor-pointer">
                  <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                  <EyeOffIcon v-else class="h-5 w-5" />
                </span>
              </div>
            </div>
  
            <!-- Tombol Login -->
            <button type="submit" class="mt-6 w-full bg-yellow-400 text-white font-bold py-3 rounded-lg shadow hover:bg-yellow-500">
              Login
            </button>
          </form>
        </div>
  
        <!-- Gambar -->
        <div class="md:w-1/2 w-full h-60 md:h-auto relative">
          <img src="/images/logo login.png" class="w-full h-full object-cover rounded-b-lg md:rounded-b-none md:rounded-r-lg" />
        </div>
      </div>
    </div>
  </template>
