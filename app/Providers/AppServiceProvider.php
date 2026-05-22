<?php

namespace App\Providers;

use App\Models\Candidature;
use App\Policies\CandidaturePolicy;
use App\Policies\EntretienPolicy;
use App\Models\Entretien;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        app()->setLocale(config('app.locale', 'fr'));

        Gate::policy(Candidature::class, CandidaturePolicy::class);
        Gate::policy(Entretien::class, EntretienPolicy::class);

        View::composer('layouts.sidebar', function ($view) {
            if (! auth()->check()) {
                return;
            }

            $user = auth()->user();

            $view->with([
                'sidebarCandidaturesCount' => $user->candidatures()->count(),
                'sidebarArchivesCount'     => Candidature::onlyTrashed()
                    ->where('user_id', $user->id)
                    ->count(),
            ]);
        });
    }
}
