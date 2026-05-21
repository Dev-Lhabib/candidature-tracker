<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use App\Models\Candidature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        unset($validated['fichier']);
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('fichier')) {
            $validated['fichier_path'] = $request->file('fichier')
                ->store('candidatures/'.auth()->id(), 'local');
        }

        $candidature = Candidature::create($validated);

        return redirect()->route('candidatures.show', $candidature)
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
        $validated = $request->validated();
        unset($validated['fichier']);

        if ($request->hasFile('fichier')) {
            if ($candidature->fichier_path) {
                Storage::disk('local')->delete($candidature->fichier_path);
            }
            $validated['fichier_path'] = $request->file('fichier')
                ->store('candidatures/'.auth()->id(), 'local');
        }

        $candidature->update($validated);

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

        if ($candidature->fichier_path) {
            Storage::disk('local')->delete($candidature->fichier_path);
        }

        $candidature->forceDelete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('candidatures.archives')
            ->with('success', 'Candidature supprimée définitivement.');
    }

    public function download(Candidature $candidature): StreamedResponse
    {
        $this->authorize('view', $candidature);

        if (!$candidature->fichier_path || !Storage::disk('local')->exists($candidature->fichier_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($candidature->fichier_path);
    }
}