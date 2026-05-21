<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Mon profil</h1>
            <p class="page-subtitle">Informations du compte et sécurité</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="card card-body">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card card-body">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
