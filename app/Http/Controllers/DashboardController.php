<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Entretien;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $userId = $user->id;

        $totalActives = $user->candidatures()->count();
        $totalArchives = Candidature::onlyTrashed()->where('user_id', $userId)->count();

        $parStatut = $user->candidatures()
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $parPriorite = $user->candidatures()
            ->select('priorite', DB::raw('count(*) as total'))
            ->groupBy('priorite')
            ->pluck('total', 'priorite');

        $totalEntretiens = Entretien::whereHas(
            'candidature',
            fn ($q) => $q->where('user_id', $userId)
        )->count();

        $entretiensAVenir = Entretien::with('candidature')
            ->whereHas('candidature', fn ($q) => $q->where('user_id', $userId))
            ->where('date_heure', '>=', now())
            ->orderBy('date_heure')
            ->limit(5)
            ->get();

        $recentCandidatures = $user->candidatures()
            ->latest()
            ->limit(5)
            ->get();

        $tauxReponse = $totalActives > 0
            ? (int) round(
                (($parStatut->get('entretien', 0) + $parStatut->get('offre', 0)) / $totalActives) * 100
            )
            : 0;

        return view('dashboard', [
            'totalActives'        => $totalActives,
            'totalArchives'       => $totalArchives,
            'totalEntretiens'     => $totalEntretiens,
            'parStatut'           => $parStatut,
            'parPriorite'         => $parPriorite,
            'statuts'             => Candidature::statuts(),
            'priorites'           => Candidature::priorites(),
            'entretiensAVenir'    => $entretiensAVenir,
            'recentCandidatures'  => $recentCandidatures,
            'tauxReponse'         => $tauxReponse,
        ]);
    }
}
