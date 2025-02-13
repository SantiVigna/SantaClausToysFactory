<?php

namespace App\Http\Controllers\Api;

use App\Models\Toy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MinimumAge;
use App\Models\ToyType;

class ToyController extends Controller
{
    const DEFAULTURLIMAGE = '/img/defaultToyImage.png';

    public function index()
    {
        $toys = Toy::all();

        return response()->json($toys, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'image' => 'url',
            'description' => 'required|string',
            'toy_type_id' => 'required|integer|min:0',
            'minimum_age_id' => 'required|integer|min:0'
        ]);

        $toyTypeID = (int)$validated['toy_type_id'];

        $toyType = ToyType::find($toyTypeID);

        if (!$toyType) {
            return response()->json(['message' => 'The toy type id does not exists'], 404);
        }

        $minimumAgeID = (int)$validated['minimum_age_id'];

        $minimumAge = MinimumAge::find($minimumAgeID);

        if (!$minimumAge) {
            return response()->json(['message' => 'The minimum age id does not exists'], 404);
        }

        $toyImage = $validated['image'] ?? $this::DEFAULTURLIMAGE;

        $toy = Toy::create([
            'name' => $validated['name'],
            'image' => $toyImage,
            'description' => $validated['description'],
            'toy_type_id' => $toyTypeID,
            'minimum_age_id' => $minimumAgeID
        ]);

        $toy->save();

        return response()->json($toy, 201);
    }

    public function show(string $id)
    {
        $toy = Toy::find($id);

        if (!$toy) {
            return response()->json(['message' => 'Toy not found'], 404);
        }

        return response()->json($toy, 200);
    }

    public function update(Request $request, string $id)
    {
        $toy = Toy::find($id);

        if (!$toy) {
            return response()->json(['message' => 'Toy not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'string',
            'image' => 'url',
            'description' => 'string',
            'toy_type_id' => 'integer|min:0',
            'minimum_age_id' => 'integer|min:0'
        ]);

        $toyTypeID = (int)($validated['toy_type_id'] ?? $toy->toy_type_id);

        $toyType = ToyType::find($toyTypeID);

        if (!$toyType) {
            return response()->json(['message' => 'The toy type id does not exists'], 404);
        }

        $minimumAgeID = (int)($validated['minimum_age_id'] ?? $toy->minimum_age_id);

        $minimumAge = MinimumAge::find($minimumAgeID);

        if (!$minimumAge) {
            return response()->json(['message' => 'The minimum age id does not exists'], 404);
        }

        $toyName = $validated['name'] ?? $toy->name;
        $toyImage = $validated['image'] ?? $toy->image;
        $description = $validated['description'] ?? $toy->description;

        $toy->update([
            'name' => $toyName,
            'image' => $toyImage,
            'description' => $description,
            'toy_type_id' => $toyTypeID,
            'minimum_age_id' => $minimumAgeID
        ]);

        $toy->save();

        return response()->json($toy, 200);
    }

    public function destroy(string $id)
    {
        $toy = Toy::find($id);

        if (!$toy) {
            return response()->json(['message' => 'Toy not found'], 404);
        }

        $toy->delete();

        return response()->json([], 204);
    }
}
