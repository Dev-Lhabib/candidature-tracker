<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\CandidatureFichier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidatureFichierController extends Controller
{
    public function download(Candidature $candidature, CandidatureFichier $fichier): StreamedResponse
    {
        $this->authorize('view', $candidature);
        abort_unless($fichier->candidature_id === $candidature->id, 404);

        if (!Storage::disk('local')->exists($fichier->chemin)) {
            abort(404);
        }

        return Storage::disk('local')->download($fichier->chemin, $fichier->nom_original);
    }

    public function destroy(Candidature $candidature, CandidatureFichier $fichier): RedirectResponse
    {
        $this->authorize('update', $candidature);
        abort_unless($fichier->candidature_id === $candidature->id, 404);

        $fichier->delete();

        return redirect()->route('candidatures.show', $candidature)
            ->with('success', 'Fichier supprimé.');
    }
}
