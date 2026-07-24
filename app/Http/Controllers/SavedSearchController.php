<?php

namespace App\Http\Controllers;

use App\Models\SavedSearch;
use Illuminate\Http\Request;

class SavedSearchController extends Controller
{
    public function index()
    {
        $client = auth()->user()->agent->clients; // adjust to a specific client context as needed
        $savedSearches = SavedSearch::whereIn('client_id', $client->pluck('id'))->get();

        return view('saved-searches.index', compact('savedSearches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'search_name' => 'required|string|max:255',
            'criteria_summary' => 'nullable|string',
            'alert_frequency' => 'nullable|in:Instantly,Daily,Weekly,Never',
            'alerts_enabled' => 'nullable|boolean',
        ]);

        $client = auth()->user()->agent->clients()->findOrFail($validated['client_id']);
        $savedSearch = $client->savedSearches()->create($validated);

        return redirect()->route('saved-searches.index')->with('success', 'Search saved.');
    }

    public function update(Request $request, SavedSearch $savedSearch)
    {
        abort_unless($savedSearch->client->agent_id === auth()->user()->agent->id, 403);

        $validated = $request->validate([
            'search_name' => 'required|string|max:255',
            'criteria_summary' => 'nullable|string',
            'alert_frequency' => 'nullable|in:Instantly,Daily,Weekly,Never',
            'alerts_enabled' => 'nullable|boolean',
        ]);

        $savedSearch->update($validated);

        return back()->with('success', 'Saved search updated.');
    }

    public function destroy(SavedSearch $savedSearch)
    {
        abort_unless($savedSearch->client->agent_id === auth()->user()->agent->id, 403);

        $savedSearch->delete();

        return back()->with('success', 'Saved search removed.');
    }
}