<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\StoresCandidatureFichiers;
use App\Http\Requests\FilterCandidatureRequest;
use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use App\Models\Candidature;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    use StoresCandidatureFichiers;

    public function index(FilterCandidatureRequest $request): View
    {
        $filters = $request->validated();
        $user = auth()->user();

        if ($user->isAdmin()) {
            $query = Candidature::with(['user', 'entretiens', 'fichiers']);
        } else {
            $query = $user->candidatures()->with(['entretiens', 'fichiers']);
        }

        if (!empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }
        if (!empty($filters['priorite'])) {
            $query->where('priorite', $filters['priorite']);
        }

        $candidatures = $query->latest()->get();

        return view('candidatures.index', [
            'candidatures' => $candidatures,
            'statuts'      => Candidature::statuts(),
            'priorites'    => Candidature::priorites(),
            'isAdminView'  => $user->isAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('candidatures.create', [
            'statuts'   => Candidature::statuts(),
            'priorites' => Candidature::priorites(),
        ]);
    }

    public function store(StoreCandidatureRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        unset($validated['fichiers']);
        $validated['user_id'] = auth()->id();

        $candidature = Candidature::create($validated);

        $flash = ['success' => 'Candidature créée avec succès.'];

        $files = $this->fichiersFromRequest($request);
        if ($files !== []) {
            $result = $this->storeFichiers($candidature, $files);
            $flash = $this->fichierUploadFlash($result, 'Candidature créée avec succès.');
        }

        return redirect()->route('candidatures.show', $candidature)->with($flash);
    }

    public function show(Candidature $candidature): View
    {
        $this->authorize('view', $candidature);
        $candidature->load(['entretiens', 'fichiers']);

        return view('candidatures.show', [
            'candidature' => $candidature,
        ]);
    }

    public function edit(Candidature $candidature): View
    {
        $this->authorize('update', $candidature);
        $candidature->load('fichiers');

        return view('candidatures.edit', [
            'candidature' => $candidature,
            'statuts'     => Candidature::statuts(),
            'priorites'   => Candidature::priorites(),
        ]);
    }

    public function update(UpdateCandidatureRequest $request, Candidature $candidature): RedirectResponse
    {
        $this->authorize('update', $candidature);
        $validated = $request->validated();
        unset($validated['fichiers']);

        $candidature->update($validated);

        $flash = ['success' => 'Candidature mise à jour.'];

        $files = $this->fichiersFromRequest($request);
        if ($files !== []) {
            $result = $this->storeFichiers($candidature, $files);
            $flash = $this->fichierUploadFlash($result, 'Candidature mise à jour.');
        }

        return redirect()->route('candidatures.show', $candidature)->with($flash);
    }

    public function destroy(Candidature $candidature): RedirectResponse
    {   
        $this->authorize('delete', $candidature);
        $candidature->delete();

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature archivée.');
    }

    public function archives(): View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $archives = Candidature::onlyTrashed()->with('user')->latest('deleted_at')->get();
        } else {
            $archives = Candidature::onlyTrashed()
                ->where('user_id', $user->id)
                ->latest('deleted_at')
                ->get();
        }

        return view('candidatures.archives', [
            'archives'    => $archives,
            'isAdminView' => $user->isAdmin(),
        ]);
    }

    public function restore(int $id)
    {
        $user = auth()->user();
        $candidature = $user->isAdmin()
            ? Candidature::withTrashed()->findOrFail($id)
            : $user->candidatures()->withTrashed()->findOrFail($id);
        $this->authorize('restore', $candidature);
        $candidature->restore();

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature restaurée.');
    }

    public function forceDelete(int $id)
    {
        $user = auth()->user();
        $candidature = $user->isAdmin()
            ? Candidature::withTrashed()->findOrFail($id)
            : $user->candidatures()->withTrashed()->findOrFail($id);
        $this->authorize('delete', $candidature);

        $candidature->load('fichiers');
        foreach ($candidature->fichiers as $fichier) {
            $fichier->delete();
        }

        $candidature->forceDelete();

        return redirect()->route('candidatures.archives')
            ->with('success', 'Candidature supprimée définitivement.');
    }
}
