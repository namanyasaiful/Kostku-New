@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="lg:p-14 p-8 w-full min-h-screen bg-cover bg-center bg-no-repeat " style="background-image: url('../assets/images/bg-auth.png');">
    <img src="{{ asset('assets/images/logo-auth.png') }}" alt="logo" class="mb-4" width="150px">
    <div class="flex lg:flex-row flex-col lg:gap-12 gap-8">
        <div class="w-full">
            <div class="flex flex-col justify-start items-start">
                <h1 class="text-primary md:text-4xl text-xl font-bold mb-4">Selamat Datang di Akun Admin</h1>
            </div>
            <div class="flex justify-center items-center">
                <img src="{{ asset('assets/icons/login-penghuni-icon.png') }}" alt="Login Penghuni" width="420px" class="lg:block hidden">
            </div>
        </div>
        <div class="w-full">
            <div class="flex justify-center lg:pt-10 pt-2">
                <x-card class="w-[500px]">
                    <h1 class="lg:text-3xl text-xl text-black font-bold mb-4">Masuk Admin</h1>
                    <form action="{{ route('sessionLogin') }}" method="POST">
                        @csrf
                        <div>
                            <x-form.input
                                label="Email"
                                name="email"
                                type="email"
                                placeholder="contoh@gmail.com" />
                        </div>
                        <div class="mt-4 mb-2"><x-form.input
                                label="Password"
                                name="password"
                                type="password"
                                placeholder="Masukkan password " /></div>
                        <x-form.button type="submit" class="my-8">Masuk</x-form.button>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection
