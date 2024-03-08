<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Vaccination;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VaccinationController extends Controller
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

        $vaccinations = Vaccination::with(['spot' => function($spot){
            $spot->with('regional');
        }, 'vaccine', 'vaccinator'])->where('society_id', $society->id)->get();

        $vaccination_status = [false, false];
        $index = 0;

        $new_vaccinations = [];

        foreach($vaccinations as $vaccination){
            if($vaccination->doctor_id != null && $vaccination->vaccine_id != null){
                $vaccination_status[$index] = true;
            }

            $new_vaccinations[] = $vaccinations[$index]; 
            $new_vaccinations[$index]["status"] = $vaccination_status[$index] == true ? "done" : "unfinished";
            
            $index++;
        }

        if($vaccinations->count() == 2){
            return response()->json([
                "vaccinations" => [
                    "first" => $new_vaccinations[0], 
                    "second" => $vaccinations[1],
                ],
            ], 200);
        }else{
            return response()->json([
                "vaccinations" => [
                    "first" => $new_vaccinations[0],
                ],
            ], 200);
        }

        


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
        $society = $this->isValidToken($request->token);

        if(!$society){
            return $this->createResponseInvalidToken("Unauthorized user");
        }

        $consultation = Consultation::where('society_id', $society->id)->first();

        if(!$consultation || $consultation->status != "accepted"){
            return response()->json([
                "message" => "Your consultation must be accepted by doctor before"
            ], 401);
        }

        $latest_vaccinations = Vaccination::where('society_id', $society->id)->get();
        $dose = 1;

        if($latest_vaccinations->count() == 1){
            global $dose;
            $miliseconds = 1000*60*60*24*30;

            $last_vaccinated_date = Vaccination::where('society_id', $society->id)->latest('id')->first()->date;
            $new_vaccinated_date = new DateTime($last_vaccinated_date);
            $new_vaccinated_date = $new_vaccinated_date->format('U')*1000;

            $vaccination_date = new DateTime($request->date);
            $vaccination_date = $vaccination_date->format('U')*1000;

            if($vaccination_date - $new_vaccinated_date < $miliseconds){
                return response()->json([
                    "message" => "Wait at least +30 days from 1st Vaccination",
                    "data" => $new_vaccinated_date,
                    "data2" => $vaccination_date,
                    "data3" => $miliseconds
                ], 401);
            }

            $dose = 2;
        }else if($latest_vaccinations->count() == 2){
            return response()->json([
                "message" => "Society has been 2x vaccinated"
            ], 401);
        }

        $validation = Validator::make($request->all(), [
            "spot_id" => "required|exists:spots,id",
            "date" => "required|date|date_format:Y-m-d"
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        try{
            Vaccination::create(["dose" => $dose, "spot_id" => $request->spot_id, "date" => $request->date, "society_id" => $society->id]);
        }catch(\Exception $e){
            return response()->json(["message" => "An error occured ". $e->getMessage()], 400);
        }

        return response()->json([
            "message" => $latest_vaccinations->count() == 1 ? "Second" : "First"." Vaccination registered!",
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Vaccination $vaccination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vaccination $vaccination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vaccination $vaccination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccination $vaccination)
    {
        //
    }
}
