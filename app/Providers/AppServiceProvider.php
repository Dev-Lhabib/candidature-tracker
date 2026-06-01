<?php

namespace App\Providers;

use App\Models\Candidature;
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
