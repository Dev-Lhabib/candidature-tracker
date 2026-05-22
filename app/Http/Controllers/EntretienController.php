<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntretienRequest;
use App\Http\Requests\UpdateEntretienRequest;
use App\Models\Candidature;
use App\Models\Entretien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EntretienController extends Controller
{
    public function create(Request $request): View
    {
        $candidatures = auth()->user()->candidatures()
            ->orderBy('entreprise')
            ->get(['id', 'entreprise', 'poste']);

        $selectedCandidatureId = null;
        if ($request->filled('candidature_id')) {
            $selectedCandidatureId = $candidatures->contains('id', (int) $request->candidature_id)
                ? (int) $request->candidature_id
                : null;
        }

        return view('entretiens.create', [
            'candidatures'            => $candidatures,
            'selectedCandidatureId'   => $selectedCandidatureId,
        ]);
    }

    public function store(StoreEntretienRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $candidature = Candidature::where('id', $data['candidature_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->authorize('update', $candidature);

        unset($data['candidature_id']);
        $candidature->entretiens()->create($data);

        return redirect()->route('candidatures.show', $candidature)
            ->with('success', 'Entretien ajouté.');
    }

    public function edit(Entretien $entretien): View
    {
        $entretien->load('candidature');
        $this->authorize('update', $entretien->candidature);

        return view('entretiens.edit', [
            'entretien' => $entretien,
        ]);
    }

    public function update(UpdateEntretienRequest $request, Entretien $entretien): RedirectResponse
    {
        $this->authorize('update', $entretien->candidature);
        $entretien->update($request->validated());

        return redirect()->route('candidatures.show', $entretien->candidature)
            ->with('success', 'Entretien mis à jour.');
    }

    public function destroy(Entretien $entretien): RedirectResponse
    {
        $this->authorize('update', $entretien->candidature);
        $candidature = $entretien->candidature;
        $entretien->delete();

        return redirect()->route('candidatures.show', $candidature)
            ->with('success', 'Entretien supprimé.');
    }
}
