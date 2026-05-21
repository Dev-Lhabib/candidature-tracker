<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\StoresCandidatureFichiers;
use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use App\Models\Candidature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    use StoresCandidatureFichiers;

    public function index(Request $request): View
    {
        $query = auth()->user()->candidatures()->with(['entretiens', 'fichiers']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $candidatures = $query->latest()->get();

        return view('candidatures.index', [
            'candidatures' => $candidatures,
            'statuts'      => Candidature::statuts(),
            'priorites'    => Candidature::priorites(),
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

        if ($request->hasFile('fichiers')) {
            $result = $this->storeFichiers($candidature, $request->file('fichiers'));
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

        if ($request->hasFile('fichiers')) {
            $result = $this->storeFichiers($candidature, $request->file('fichiers'));
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
        $archives = Candidature::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest('deleted_at')
            ->get();

        return view('candidatures.archives', [
            'archives' => $archives,
        ]);
    }

    public function restore(int $id)
    {
        $candidature = Candidature::withTrashed()->where('id', $id)->firstOrFail();
        $this->authorize('restore', $candidature);
        $candidature->restore();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature restaurée.');
    }

    public function forceDelete(int $id)
    {
        $candidature = Candidature::withTrashed()->where('id', $id)->firstOrFail();
        $this->authorize('delete', $candidature);

        $candidature->load('fichiers');
        foreach ($candidature->fichiers as $fichier) {
            $fichier->delete();
        }

        $candidature->forceDelete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('candidatures.archives')
            ->with('success', 'Candidature supprimée définitivement.');
    }
}
