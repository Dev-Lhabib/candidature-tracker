<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\CandidatureFichier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidatureFichierController extends Controller
{
    public function download(Candidature $candidature, CandidatureFichier $fichier): StreamedResponse|RedirectResponse
    {
        $this->authorize('view', $candidature);

        if (!Storage::disk('local')->exists($fichier->chemin)) {
            return redirect()
                ->route('candidatures.show', $candidature)
                ->with('warning', 'Ce fichier est introuvable sur le serveur.');
        }

        return Storage::disk('local')->download($fichier->chemin, $fichier->nom_original);
    }

    public function destroy(Candidature $candidature, CandidatureFichier $fichier): RedirectResponse
    {
        $this->authorize('update', $candidature);

        $fichier->delete();

        return redirect()
            ->route('candidatures.show', $candidature)
            ->with('success', 'Fichier supprimé.');
    }
}
