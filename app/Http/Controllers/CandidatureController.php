<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use App\Models\Candidature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    public function index(Request $request): View
    {
        $query = auth()->user()->candidatures()->with('entretiens');

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
        $validated['user_id'] = auth()->id();
        Candidature::create($validated);

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature créée avec succès.');
    }

    public function show(Candidature $candidature): View
    {
        $this->authorize('view', $candidature);
        $candidature->load('entretiens');

        return view('candidatures.show', [
            'candidature' => $candidature,
        ]);
    }

    public function edit(Candidature $candidature): View
    {
        $this->authorize('update', $candidature);

        return view('candidatures.edit', [
            'candidature' => $candidature,
            'statuts'     => Candidature::statuts(),
            'priorites'   => Candidature::priorites(),
        ]);
    }

    public function update(UpdateCandidatureRequest $request, Candidature $candidature): RedirectResponse
    {
        $this->authorize('update', $candidature);
        $candidature->update($request->validated());

        return redirect()->route('candidatures.show', $candidature)
            ->with('success', 'Candidature mise à jour.');
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

    public function restore(int $id): RedirectResponse
    {
        $candidature = Candidature::withTrashed()->findOrFail($id);
        $this->authorize('restore', $candidature);
        $candidature->restore();

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature restaurée.');
    }
}