<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $society = $this->isValidToken($request->token);

        if(!$society){
            return $this->createResponseInvalidToken("Unauthorized user");
        }

        $spots = Spot::with('available_vaccines')->where('regional_id', $society->regional_id)->get();

        $vaccines = [];
        $all_vaccines = Vaccine::all()->pluck('name')->toArray();

        $spotsDatas = $spots;
        $index = 0;

        foreach($spotsDatas as $spotsData){
            $vaccine_collection = [];
            foreach($spotsData["available_vaccines"] as $vaccine){
                $vaccine_collection[] = $vaccine->name;
            }
            $vaccines[] = $vaccine_collection;
            $index++;
        }
          
        for($i = 0; $i < count($spotsDatas); $i++){
            for($j = 0; $j < count($vaccines[$i]); $j++){
                $spotsDatas[$i]["available_vaccines"][$j] = $vaccines[$i][$j];
            }
        }

        if(is_null($spots->first())){
            return response()->json(["message" => "Spot not found!"], 404);
        }

        return response()->json([
            "spots" => $all_vaccines
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $society = $this->isValidToken($request->token);

        if(!$society){
            return $this->createResponseInvalidToken("Unauthorized user");
        }

        $spots = Spot::where('id', $id)->get();

        if($date = $request->date){

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spot $spot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spot $spot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        //
    }
}
