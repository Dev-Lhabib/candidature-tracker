<?php

namespace App\Providers;

use App\Models\Candidature;
use App\Models\Entretien;
use App\Policies\CandidaturePolicy;
use App\Policies\EntretienPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Candidature::class, CandidaturePolicy::class);
        Gate::policy(Entretien::class, EntretienPolicy::class);
    }
}