<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\UserPasswordRule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;


class ResetPasswordController extends Controller
{
    public function ForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $matchQuery = ['email' => $request->email];
        $user = User::where($matchQuery)->first();
        if ($user) {
            $row = PasswordReset::where($matchQuery)->first();
            if ($row) {
                DB::table('password_resets')->where($matchQuery)->delete();
            }
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Str::random(60),
                'created_at' => Carbon::now()
            ]);
            $row = PasswordReset::where($matchQuery)->first();
            $this->send_reset_email($row);
            return response()->json([
                'status' => 200,
                'message' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'message' => 'failed'
            ]);
        }
    }

    public function send_reset_email($user)
    {

        $data = array('token' => $user->token, 'email' => $user->email);


        Mail::send('reset_password', $data, function ($message) use ($user) {
            $message->bcc($user->email)->subject('Reset Password');
            $message->from('mshata176@gmail.com', 'Furniture Store');
        });
    }

    public function ResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:3', 'max:9', new UserPasswordRule]
        ]);


        $matchQuery = ['email' => $request->email];
        $row = PasswordReset::where($matchQuery)->first();
        if ($row->token == $request->token) {
            $user = User::where($matchQuery)->first();
            $user->password = Hash::make($request->password);
            $user->save();
            DB::table('password_resets')->where($matchQuery)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'failed'
            ]);
        }
    }
}
