<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::get()->all();
        return response()->json([
            'status' => 'success',
            'data' => $user

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "mobile" => "required",
            "email" => "required|email",
            "password" => "required|confirmed",

        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "Failed",
                "message" => $validator->errors(),
            ], 422);
        }
        if (User::where('email', $request->email)->first()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Email already exists'
            ]);
        }


        $user = User::create([
            "name" => $request->name,
            "mobile" => $request->mobile,
            "email" => $request->email,
            "password" => Hash::make($request->password),

        ]);




        if ($user) {
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                // "token" => "$token",
                "status" => "success",
                "message" => "user Registered Successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => "Failed",
                "message" => "Something went wrong",
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            "email" => "required|email",
            "password" => "required",

        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "Failed",
                "message" => $validator->errors(),
            ], 422);
        }
        $user = User::where("email", $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "status" => "Failed",
                "message" => "Invalid Credentials",
            ], 401);
        }
        $token = $user->createToken($request->email)->plainTextToken;
        return response()->json([
            "token" => "$token",
            "status" => "success",
            "message" => "User Logged in Successfully",
        ], 200);
    }

    /**
     * Display the specified resource.
     * 
     */
    public function logout(Request $request)
    {
      $logout =  $request->user()->tokens()->delete();
      if($logout){
        return response()->json([
            "status" => "success",
            "message" => "User Logged out Successfully",
            ], 200);
        }else{
            return response()->json([
                "status" => "Failed",
                "message" => "Failed to logout",
                ], 401);
                }
        

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    "status" => "Failed",
                    "message" => "Password is required",
                    ], 401);

             }
             $LoggedUser = $request->user(); 
             $LoggedUser->password = Hash::make($request->password);
             $LoggedUser->save();
             return response()->json([
                "status" => "success",
                "message" => "Password changed Successfully",
                ], 200);

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
