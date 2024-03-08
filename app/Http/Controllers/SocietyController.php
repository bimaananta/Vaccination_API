<?php

namespace App\Http\Controllers;

use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocietyController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "id_card_number" => "required|exists:societies,id_card_number",
            "password" => "required"
        ]);

        if($validation->fails()){
            return $this->createResponseValidate($validation->errors());
        }

        $user = Society::where('id_card_number', $request->id_card_number)->first();

        if(!$user || $user->password != $request->password){
            return response()->json([
                "message" => "Wrong username and password"
            ], 401);
        }

        $society = Society::with('regional')->where('id_card_number', $user->id_card_number)->first();

        $token = md5($user->id_card_number);
        $society->login_tokens = $token;

        try{
            $society->save();
        }catch(\Exception $e){
            return response()->json([
                "message" => "Failed to login ". $e->getMessage() 
            ],400);
        }

        $data = $society;
        $data["token"] = $token;

        return response()->json($data, 200);
    }

    public function logout(Request $request)
    {
        $society = $this->isValidToken($request->token);
        
        if(!$society){
            return $this->createResponseInvalidToken("Invalid Token");
        }

        $society->login_tokens = null;

        try{
            $society->save();
        }catch(\Exception $e){
            return response()->json([
                "message" => "Error while logout ". $e->getMessage(),
            ], 400);
        }

        return response()->json([
            "message" => "Logout success"
        ], 200);
    }
}
