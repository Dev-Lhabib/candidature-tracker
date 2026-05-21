<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CandidatureTracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
        <div class="min-h-screen lg:flex">
            {{-- Mobile overlay --}}
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
                style="display: none;"
                @click="sidebarOpen = false"
            ></div>

            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col min-h-screen lg:min-w-0">
                {{-- Top bar --}}
                <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-4 border-b border-slate-200/80 bg-white/80 backdrop-blur-md px-4 sm:px-6 lg:px-8">
                    <button
                        type="button"
                        class="lg:hidden p-2 -ml-2 rounded-xl text-slate-600 hover:bg-slate-100"
                        @click="sidebarOpen = true"
                        aria-label="Ouvrir le menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="flex-1 min-w-0">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-lg font-semibold text-slate-900 truncate">{{ config('app.name') }}</h1>
                        @endisset
                    </div>

                    <div class="hidden sm:flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate max-w-[180px]">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                </header>

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                        <x-flash-alert type="success" :message="session('success')" />
                    </div>
                @endif

                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
