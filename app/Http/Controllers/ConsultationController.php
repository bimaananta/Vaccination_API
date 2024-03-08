<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
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

        $consultations = Consultation::with('doctor')->where('society_id', $society->id)->latest('id')->get();

        if(is_null($consultations->first())){
            return response()->json([
                "message" => "Consultation not found!"
            ], 404);
        }

        return response()->json(["consultation" => $consultations], 200);

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

        $validation = Validator::make($request->all(), [
            "disease_history" => "required|string",
            "current_symptoms" => "required|string"
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $consultation = new Consultation();
        $consultation->disease_history = $request->disease_history;
        $consultation->current_symptoms = $request->current_symptoms;
        $consultation->society_id = $society->id;

        try{
            $consultation->save();
        }catch(\Exception $e){
            return response()->json([
                "message" => "Failed to create consultation ". $e->getMessage()
            ], 400);
        }

        return response()->json([
            "message" => "Request consultation successful"
        ], 200);


    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consultation $consultation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consultation $consultation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultation $consultation)
    {
        //
    }
}
