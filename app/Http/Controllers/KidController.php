<?php

namespace App\Http\Controllers;

use App\Models\Kid;
use App\Models\Toy;
use Illuminate\Http\Request;

class KidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kids = Kid::get();

        return view('santa', compact('kids'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /* public function create()
    {
        //
    } */

    /**
     * Store a newly created resource in storage.
     */
    /*  public function store(Request $request)
    {
        //
    } */

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kid = Kid::find($id);

        if (!$kid) {
            return $this->index();
        }
        return view('santaShow', compact('kid'));
    }

    /*  /**
     * Show the form for editing the specified resource.
     */
    /*  public function edit(Kid $kid)
    {
        //
    } */

    /**
     * Update the specified resource in storage.
     */
    /*  public function update(Request $request, Kid $kid)
    {
        //
    }
 */
    /**
     * Remove the specified resource from storage.
     */
    /* public function destroy(Kid $kid)
    {
        //
    } */

    public function listOfGifts()
    {
        $toys = Toy::with('toyType.associated')->get();

        $goodKids = Kid::where('attitude', true)
            ->where('age', '<', 18)
            ->get();

        $goodAdults = Kid::where('attitude', true)
            ->where('age', '>=', 18)
            ->get();

        $badKids = Kid::where('attitude', false)
            ->get();

        /* $this->generateSpecialGifts($badKids, 'Charcoal');
        $this->generateSpecialGifts($adultKids, 'Trip'); */

        return response()->json([
            'goodKids' => $goodKids,
            'badKids' => $badKids,
            'adultKids' => $goodAdults,
            'toys' => $toys
        ]);
    }

    private function generateSpecialGifts($kids, $toyType)
    {
        $modelNameSpace = 'App\\Models\\' . $toyType;

        foreach ($kids as $kid) {
            do {
                $toy = Toy::with('toyType')->inRandomOrder()->first();

                $type = $toy->toyType->associated_type;
            } while ($modelNameSpace != $type);
        }
    }
}
