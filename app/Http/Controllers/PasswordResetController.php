<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\password_reset_token;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\password_reset_tokenMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Mail\Message;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
      
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
            ], 422);
        }

      
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'error' => 'User not found',
            ], 404);
        }
        $token = Str::random(60);
        password_reset_token::Create(
            [
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );
        
       // dump("http://127.0.0.1:8000/api/user/reset/" . $token);
        //sending email with password reset view
        Mail::send('reset', ['token' => $token], function(Message $message)use($email){
            $message->subject('Reset Your Password');
            $message->to($email);
        });
        

        return response()->json([
            'message' => 'Reset password link sent on your email',
            'status' => 'success'
        ]);
    }
}
