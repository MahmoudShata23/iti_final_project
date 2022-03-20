<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Http\Resources\WishlistResource;
use DB;

class WishlistController extends Controller
{
    public function AddToWishlist(Request $request, $product_id)
    {
        $matchQuery=['user_id'=>$request->user_id,'product_id'=>$product_id];
        $wishItems=Wishlist::where($matchQuery)->get();
        if(count($wishItems)>0){
            return response()->json([
                'status' => 200,
                'message' => 'Item already added to wishlist',
            ]);

        }else{
            $validator = $request->validate([
                'user_id'=>'required',
                'product_id'=>'required']);
            $wishItem=Wishlist::create($validator);
           if ($wishItem){
               return response()->json([
                   'status' => 200,
                   'message' => 'Product updated succesfully',
               ]);
           }else{
               return response()->json([
                   'status'=>422,
                   'errors'=> $wishItem->messages()
               ]);}}
    }
    public function ViewWishlist()
    {
        $wishlist = Wishlist::all();

        return response()->json([
            'status' => 200,
            'products' => $wishlist,
        ]);
    }
    public function GetWishlistProduct(Request $request)
    {
        $matchQuery=['user_id'=>$request->user_id];
        $wishItems=Wishlist::where($matchQuery)->get();
        $items=array();
        foreach($wishItems as $wishItem){
            $item =new Wishlist;
            $item->user_id=$wishItem->user_id;
            $item->product_id=$wishItem->product_id;
            $item->updated_at=$wishItem->updated_at;
            $item->created_at=$wishItem->created_at;
            array_push($items,$item);
        }
        return response()->json([
            'status' => 200,
            'products' => $wishItems,
        ]);
    }
    public function RemoveWishlistProduct(Request $request,$product_id)
    {
        $matchQuery=['user_id'=>$request->user_id,'product_id'=>$product_id];
        $item = Wishlist::where($matchQuery)->get();
        if($item){
            $deleted=DB::table('wishlists')->where($matchQuery)->delete();
            if($deleted){
            return response()->json([
                'status'=>200,
                'message'=>'Wishlist product deleted succesfully']);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'No Wishlist product To Delete']);
            }  
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'No Wishlist product To Delete']);
        }
    }

}
