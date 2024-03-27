<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the cars.
     */
    public function index()
    {
        return response()->json(Car::all());
    }

    /**
     * Estimated price of a car (quotation)
     */
    public function estimatePrice(Request $request)
    {
        $averagePrice = Car::where('marque', $request->marque)
            ->where('modele', $request->modele)
            ->where('annee', $request->annee)
            ->where('kilometrage', $request->kilometrage)
            ->where('puissance', $request->puissance)
            ->where('motorisation', $request->motorisation)
            ->where('carburant', $request->carburant)
            ->where('options', $request->options)
            ->avg('prix');

        return response()->json(['estimated_price' => $averagePrice]);
    }
}
