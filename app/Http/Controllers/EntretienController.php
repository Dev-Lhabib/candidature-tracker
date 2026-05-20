<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntretienRequest;
use App\Http\Requests\UpdateEntretienRequest;
use App\Models\Candidature;
use App\Models\Entretien;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EntretienController extends Controller
{
    public function store(StoreEntretienRequest $request, Candidature $candidature): RedirectResponse
    {
        $this->authorize('update', $candidature);
        $candidature->entretiens()->create($request->validated());

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