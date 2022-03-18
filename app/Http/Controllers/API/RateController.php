<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\Rate;
use DB;

class RateController extends Controller
{
    public function AddProductRate($product_id,Request $request){
        $matchQuery=['user_id'=>$request->user_id,'product_id'=>$product_id];
        $RateItems=Rate::where($matchQuery)->get();
        if(count($RateItems)>0){
            $validator = $request->validate([
                'rate'=>'required'
            ]);
            $update = Rate::where($matchQuery)->update($validator);
            if ($update){
                return response()->json([
                    'status' => 200,
                    'message' => 'Product updated succesfully',
                ]);
            }else{
                return response()->json([
                    'status'=>422,
                    'errors'=> $validator->messages()
                ]);
            }

        }else{
            $validator = $request->validate([
                'user_id'=>'required',
                'product_id'=>'required',
                'rate'=>'required'
            ]);
            $RateItems=Rate::create($validator);
            if ($RateItems){
                return response()->json([
                    'status' => 200,
                    'message' => 'Rate added succesfully',
                ]);
            }else{
                return response()->json([
                    'status'=>422,
                    'errors'=> $RateItems->messages()
                ]);}}
    }

    public function DeleteProductRate($product_id,Request $request){
        $matchQuery=['user_id'=>$request->user_id,'product_id'=>$product_id];
        $item = Rate::where($matchQuery)->get();
        if($item){
            DB::table('rates')->where($matchQuery)->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Rate deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Wishist ID not found']);
        }
    }


    public function updateProductavgRate($product_id){
        $productRates=Rate::where(['product_id'=>$product_id])->get();
        $avg=0;
        foreach($productRates as $productRate){
            $avg+=$productRate->rate;
        }
        $ratingCount=count($productRates);
        if($ratingCount>0){
            $avg=$avg/$ratingCount;
        }
        $updata=array('avgRate' => $avg,'updated_at'=>\Carbon\Carbon::now());
        Product::where(['id'=>$product_id])->limit(1)->update($updata);
    }
}
