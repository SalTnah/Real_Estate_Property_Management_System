<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Property;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // This method is triggered when the user clicks the "Favorite" button
    public function toggleFavorite(Request $request, Property $property)
    {
        // 1. Get the currently logged-in client (assuming your clients log in)
        // If the Agent is the one logging in on behalf of a client, you would pass the $client_id in the request instead.
        $client = auth()->user()->client; 

        // 2. Toggle the favorite (this is where your code goes!)
        $client->favoriteProperties()->toggle($property->id);

        // 3. Send the user back to the page they were on with a success message
        return back()->with('success', 'Favorites updated!');
    }
}