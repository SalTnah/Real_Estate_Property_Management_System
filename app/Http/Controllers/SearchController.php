<?php

namespace App\Http\Controllers;

use App\Models\Search;
use App\Models\Property;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $searches = auth()->user()->agent->searches()->latest()->get();
        return view('searches.index', compact('searches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'query_term' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'price_min' => 'nullable|numeric',
            'price_max' => 'nullable|numeric',
            'beds' => 'nullable|integer',
            'sort' => 'nullable|string',
            'filter' => 'nullable|array',
            'map_view' => 'nullable|boolean',
        ]);

        auth()->user()->agent->searches()->create($validated);

        $results = Property::query()
            ->when($validated['location'] ?? null, fn ($q, $v) => $q->where('city', 'like', "%{$v}%"))
            ->when($validated['price_min'] ?? null, fn ($q, $v) => $q->where('price', '>=', $v))
            ->when($validated['price_max'] ?? null, fn ($q, $v) => $q->where('price', '<=', $v))
            ->when($validated['beds'] ?? null, fn ($q, $v) => $q->where('bedrooms', '>=', $v))
            ->get();

        return view('searches.results', compact('results'));
    }

    public function destroy(Search $search)
    {
        abort_unless($search->agent_id === auth()->user()->agent->id, 403);

        $search->delete();

        return back()->with('success', 'Search history removed.');
    }
}