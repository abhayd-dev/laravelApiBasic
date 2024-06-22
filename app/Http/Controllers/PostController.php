<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::all();
        if($post->count() > 0){
            return response()->json([
                "status"=> "success",
                "message"=> "Employee found",
                "data"=> $post
                ],200);
        }else{
            return response()->json([
                "status"=> "Failed ",
                "message"=> "Employee Not found",

            ],404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "age" => "required",
            "salary" => "required",
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                "status" => "Failed",
                "message" => $validator->errors(),
            ], 422);
        }
    
        $post = Post::create([
            "name" => $request->name,
            "email" => $request->email,
            "age" => $request->age,
            "salary" => $request->salary,
        ]);
    
        if ($post) {
            return response()->json([
                "status" => "success",
                "message" => "Employee Created",
            ], 200);
        } else {
            return response()->json([
                "status" => "Failed",
                "message" => "Something went wrong",
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $id)
    {
        $post = Post::find($id);

        if($post){
            return response()->json([
                "status"=> "Success",
                "message"=>"Employee Found",
                "data"=>$post
                ], 200);
        }else{
            return response()->json([
                "status"=> "Failed",
                "message"=> "Not Found"
                ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
    $validator = Validator::make($request->all(), [
        "name" => "required",
        "email" => "required|email",
        "age" => "required",
        "salary" => "required",
       
    ]);
    
    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            "status" => "Failed",
            "message" => $validator->errors(),
        ], 422); 
    }
    
 
    $post = Post::find($id);
    $post->update([
        "name" => $request->name,
        "email" => $request->email,
        "age" => $request->age,
        "salary" => $request->salary,
    ]);
    

    if ($post) {
    
        return response()->json([
            "status" => "success",
            "message" => "Student Updated",
        ], 200);
    } else {
       
        return response()->json([
            "status" => "Failed",
            "message" => "Something went wrong",
        ], 404);
    }

}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         $post = Post::find($id);
   
        if($post){
            $post->delete();
            return response()->json([
                "status"=>"success",
                "message"=>"Deleted Successfully"
            ],200);
        }else{
            return response()->json([
                "status"=>"Failed",
                "message"=>"Not found"
            ],404);
        }
    }
            
    
}
