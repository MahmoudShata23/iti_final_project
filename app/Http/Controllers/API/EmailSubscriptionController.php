<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;

class EmailSubscriptionController extends Controller
{
    public function SubscribeToUpdates(Request $request){
        $validator = $request->validate([
            'email' => 'required|email'
        ]);
        $matchQuery=['email'=>$request->email];
        
        $subscription=Subscription::where($matchQuery)->first();
        if($subscription){
            return response()->json([
                'status'=>200,
                'message'=>'email existed'
            ]);
        }

        $subscription=Subscription::create($validator);

        if($subscription){
            return response()->json([
                'status'=>200,
                'message'=>'success'
            ]);
        }else{
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages()
            ]);
        }
    }

    public function UnsubscribeToUpdates($email){
        $matchQuery=['email'=>$email];
        
        $subscription=Subscription::where($matchQuery)->first();

        if($subscription){
            $subscription->delete();
            return response()->json([
                'status'=>200,
                'message'=>'success'
            ]);
        }else{
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages()
            ]);
        }
    }
}
