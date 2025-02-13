<?php

namespace App\Http\Controllers;

use App\Models\Kid;
use App\Models\Toy;
use App\Models\Gender;
use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kids = Kid::all();

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
            return redirect()->route('santa');
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
        try {
            $this->removeKidToysFromDB();

            $allKids = $this->getKidsFromDB();

            $listOfGifts = $this->generateListOfGifts($allKids);

            $this->createKidToysToDB($listOfGifts);
        } catch (Exception $e) {
            return redirect()->route('santa');
        }

        return view('gift', compact('listOfGifts'));
    }

    private function getKidsFromDB()
    {
        $goodKids = $this->getGoodKids();

        $goodAdults = $this->getAdultsKids();

        $badKids = $this->getBadKids();

        return [
            'goodKids' => $goodKids,
            'goodAdults' => $goodAdults,
            'badKids' => $badKids
        ];
    }

    private function getGoodKids()
    {
        return Kid::where('attitude', true)
            ->where('age', '<', 18)
            ->get();
    }

    private function getAdultsKids()
    {
        return Kid::where('age', '>=', 18)
            ->get();
    }

    private function getBadKids()
    {
        return Kid::where('attitude', false)
            ->where('age', '<', 18)
            ->get();
    }

    private function generateListOfGifts($allKids)
    {
        $listOfGifts = [];

        $goodKids = $allKids['goodKids'];
        $goodAdults = $allKids['goodAdults'];
        $badKids = $allKids['badKids'];

        if ($goodKids)
            $listOfGifts = $this->generateGifts($listOfGifts, $goodKids, 'Plaything', 2);

        if ($goodAdults)
            $listOfGifts = $this->generateGifts($listOfGifts, $goodAdults, 'Trip', 1);

        if ($badKids)
            $listOfGifts = $this->generateGifts($listOfGifts, $badKids, 'Charcoal', 1);

        return $listOfGifts;
    }

    private function generateGifts($listOfGifts, $kids, $toyType, $numbersOfGifts)
    {
        $MODELBASENAMESPACE = 'App\\Models\\';

        $playthingModelNameSpace = $MODELBASENAMESPACE . 'Plaything';
        $modelNameSpace = $MODELBASENAMESPACE . $toyType;

        foreach ($kids as $kid) {
            $listOfToys = [];

            for ($i = 0; $i < $numbersOfGifts; $i++) {
                $toy = $modelNameSpace == $playthingModelNameSpace
                    ? $this->generateNormalGifts($listOfToys, $kid, $modelNameSpace)
                    : $this->generateSpecialGifts($modelNameSpace);

                $listOfToys[] = $toy;
            }

            $listOfGifts[] = [
                $kid,
                $listOfToys
            ];
        }

        return $listOfGifts;
    }

    private function generateNormalGifts($listOfToys, $kid, $modelNameSpace)
    {
        $DEFAULTMAXAGE = 99;

        $kidAge = $kid->age;

        do {
            $toy = Toy::with(['toyType', 'minimumAge'])
                ->inRandomOrder()
                ->first();

            $minToyMinimumAge = $toy->minimumAge->min;
            $maxToyMinimumAge = $toy->minimumAge->max ?? $DEFAULTMAXAGE;

            $type = $toy->toyType->associated_type;

            $exists = $this->checkIfListOfGiftIncludesGift($listOfToys, $toy);
        } while (
            $exists
            || $modelNameSpace != $type
            || !($minToyMinimumAge <= $kidAge && $maxToyMinimumAge >= $kidAge)
        );

        return $toy;
    }

    private function generateSpecialGifts($modelNameSpace)
    {
        do {
            $toy = Toy::with('toyType')
                ->inRandomOrder()
                ->first();

            $type = $toy->toyType->associated_type;
        } while ($modelNameSpace != $type);

        return $toy;
    }

    private function checkIfListOfGiftIncludesGift($listOfToys, $toy)
    {
        $exists = false;
        $lengthOfToys = count($listOfToys);

        $toyName = $toy['name'];

        for ($i = 0; !$exists && $i < $lengthOfToys; $i++) {
            $listOfToyName = $listOfToys[$i]['name'];

            if ($listOfToyName === $toyName)
                $exists = true;
        }

        return $exists;
    }

    private function createKidToysToDB($listOfGifts)
    {
        foreach ($listOfGifts as $gift) {
            $kidID = $gift[0]['id'];
            $listOfToys = $gift[1];

            $kid = Kid::find($kidID);

            foreach ($listOfToys as $toy) {
                $toyID = $toy['id'];

                $kid->toys()->attach($toyID);
            }
        }
    }

    private function removeKidToysFromDB()
    {
        DB::table('kid_toy')->truncate();
    }
}
