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
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            {{-- Left panel branding --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-slate-900 via-brand-950 to-brand-800 p-12 flex-col justify-between">
                <div class="absolute inset-0 opacity-30">
                    <div class="absolute top-20 left-10 w-72 h-72 bg-brand-500 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-10 right-10 w-96 h-96 bg-violet-600 rounded-full blur-3xl"></div>
                </div>
                <div class="relative">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center ring-1 ring-white/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">CandidatureTracker</span>
                    </div>
                </div>
                <div class="relative max-w-md">
                    <h2 class="text-3xl font-bold text-white leading-tight">Pilotez vos candidatures en toute clarté</h2>
                    <p class="mt-4 text-slate-300 text-lg leading-relaxed">Offres, entretiens, statuts et archives — un seul espace pour votre recherche d'emploi.</p>
                </div>
                <p class="relative text-sm text-slate-500">Laravel 13 · Breeze · MySQL</p>
            </div>

            {{-- Auth form --}}
            <div class="flex-1 flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-20 bg-surface">
                <div class="sm:mx-auto sm:w-full sm:max-w-md">
                    <div class="lg:hidden flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-slate-900">CandidatureTracker</span>
                    </div>

                    <div class="card card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
