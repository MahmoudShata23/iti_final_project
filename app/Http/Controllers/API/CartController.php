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
                'Cart' => $cartItems,
            ]);

        }else{
            return response()->json([
                'Cart' => $cartItems
            ]);
        }
    }

    public function addProductToCart($product_id,Request $request)
    {
        $matchQuery=['user_id'=>$request->user_id,'product_id'=>$product_id];

        $cartItem=Cart::where($matchQuery)->first();

        if($cartItem!=null){

            $updata = array('count' => ++$cartItem->count,'updated_at'=>\Carbon\Carbon::now());
            Cart::where($matchQuery)->limit(1)->update($updata);
            return response()->json([
                'status' => 200,
                'message' => 'Item increament  to CART',
            ]);

        }else{
            $validator = $request->validate([
                'user_id'=>'required',
                'product_id'=>'required']);
            $CartItem=Cart::create($validator);
            if ($CartItem){
                return response()->json([
                    'status' => 200,
                    'message' => 'Product updated succesfully',
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
        $matchQuery=['user_id'=>$request->user_id,'product_id'=>$product_id];
        $item = Cart::where($matchQuery)->get();
        if($item){
            $item->delete();
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
