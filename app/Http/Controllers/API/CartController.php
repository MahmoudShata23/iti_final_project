<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Resources\CartResource;
use DB;

class CartController extends Controller
{
    public function viewAllCarts(){
        $Cart = Cart::all();

        return response()->json([
            'status' => 200,
            'Cart' => $Cart,
        ]);
    }

    public function MyCart(Request $request)
    {
        $request->validate([
            'user_id'=>'required',
        ]);
        $matchQuery=['user_id'=>$request->user_id];
        $cartItems=Cart::where($matchQuery)->get();
        if ($cartItems) {
            $items = array();
            foreach ($cartItems as $cartItem) {
                $item = new Cart;
                $item->user_id = $cartItem->user_id;
                $item->product_id = $cartItem->product_id;
                $item->count = $cartItem->count;
                $item->updated_at = $cartItem->updated_at;
                $item->created_at = $cartItem->created_at;

                array_push($items, $item);
            }
            return response()->json([
                'status' => 200,
                'Cart' => CartResource::collection($cartItems),
            ]);

        }else{
            return response()->json([
                'Cart' => $cartItems
            ]);
        }
    }

    public function addProductToCart(Request $request)
    {
        $validator = $request->validate([
            'user_id'=>'required',
            'product_id'=>'required'
        ]);
            
        $matchQuery=['user_id'=>$request->user_id,'product_id'=>$request->product_id];

        $cartItem=Cart::where($matchQuery)->first();

        if($cartItem!=null){

            $updata = array('count' => ++$cartItem->count,'updated_at'=>\Carbon\Carbon::now());
            Cart::where($matchQuery)->limit(1)->update($updata);
            return response()->json([
                'status' => 200,
                'message' => 'Item increament  to CART',
            ]);

        }else{
            $CartItem=new Cart;
            $CartItem->user_id=$request->user_id;
            $CartItem->product_id=$request->product_id;
            $CartItem->count=1;
            if ($CartItem){
                $CartItem->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Product added succesfully',
                ]);
            }else{
                return response()->json([
                    'errors'=> $CartItem
                ]);
            }
        }
    }

    public function RemoveCartProduct($product_id,Request $request)
    {
        $request->validate([
            'user_id'=>'required'
        ]);
        $matchQuery=['user_id'=>$request->user_id,'product_id'=>$product_id];
        $item = Cart::where($matchQuery)->first();
        if($item){
            DB::table('carts')->where($matchQuery)->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Cart deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Cart ID not found']);
        }
    }
}
