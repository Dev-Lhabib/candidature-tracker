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

        $scopeUser = fn ($q) => $q->where('user_id', $userId);

        $entretiensAVenirQuery = Entretien::query()
            ->with('candidature')
            ->whereHas('candidature', $scopeUser)
            ->where('date_heure', '>=', now())
            ->orderBy('date_heure');

        $entretiensAVenirCount = (int) (clone $entretiensAVenirQuery)->count();
        $prochainEntretien = (clone $entretiensAVenirQuery)->first();
        $entretiensAVenir = (clone $entretiensAVenirQuery)->limit(8)->get();

        $entretiensAujourdhui = (clone $entretiensAVenirQuery)
            ->whereDate('date_heure', today())
            ->get();

        $entretiensCetteSemaine = (int) (clone $entretiensAVenirQuery)
            ->where('date_heure', '<=', now()->endOfWeek())
            ->count();

        $sansPreparationQuery = (clone $entretiensAVenirQuery)
            ->where(function ($q) {
                $q->whereNull('notes_preparation')->orWhere('notes_preparation', '');
            });

        $entretiensSansPreparation = (clone $sansPreparationQuery)->limit(5)->get();
        $entretiensSansPreparationCount = (int) (clone $sansPreparationQuery)->count();

        $resultatEnAttenteQuery = Entretien::query()
            ->with('candidature')
            ->whereHas('candidature', $scopeUser)
            ->where('date_heure', '<', now())
            ->where('resultat', 'en_attente')
            ->orderByDesc('date_heure');

        $entretiensResultatEnAttente = (clone $resultatEnAttenteQuery)->limit(5)->get();
        $entretiensResultatEnAttenteCount = (int) (clone $resultatEnAttenteQuery)->count();

        $candidaturesARelancer = $user->candidatures()
            ->whereIn('statut', ['en_attente', 'relance'])
            ->count();

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
            'totalActives'                     => $totalActives,
            'totalArchives'                    => $totalArchives,
            'entretiensAVenirCount'            => $entretiensAVenirCount,
            'entretiensCetteSemaine'           => $entretiensCetteSemaine,
            'entretiensAujourdhui'             => $entretiensAujourdhui,
            'entretiensSansPreparation'        => $entretiensSansPreparation,
            'entretiensSansPreparationCount'   => $entretiensSansPreparationCount,
            'entretiensResultatEnAttente'      => $entretiensResultatEnAttente,
            'entretiensResultatEnAttenteCount' => $entretiensResultatEnAttenteCount,
            'candidaturesARelancer'            => $candidaturesARelancer,
            'prochainEntretien'                => $prochainEntretien,
            'parStatut'                        => $parStatut,
            'parPriorite'                      => $parPriorite,
            'statuts'                          => Candidature::statuts(),
            'priorites'                        => Candidature::priorites(),
            'resultats'                        => Entretien::resultats(),
            'entretiensAVenir'                 => $entretiensAVenir,
            'recentCandidatures'               => $recentCandidatures,
            'tauxReponse'                      => $tauxReponse,
        ]);
    }
}
