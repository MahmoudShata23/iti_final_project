<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use  App\Models\Order;

class AllUserController extends Controller
{
    public function MyOrders($order_id)
    {
        $order = Order::with('user')->where('id', $order_id)->get();
        $order_item = OrderItem::with('product')->where('order_id', $order_id)->
        orderBy('id', 'DESC')->get();

        if($order_item){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already exits']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not already exits']);}

    }

    public function DetailsOrder($id)
    {
        $order = Order::find($id);
        if($order){
            return response()->json([
                'status'=>200,
                'order'=>$order

            ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No order id found'

            ]);

        }

    }

    public function ReturnOrder(Request $request, $order_id)
    {
        $order = Order::with('user')->where('id', $order_id)->first();
        $order_item = OrderItem::with('product')->where('order_id', $order_id)->
        orderBy('id', 'DESC')->get();

        if($order_item){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already exits']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not already exits']);}

    }

    public function ReturnedOrderList()
    {
        $Order = Order::all();

        if($Order){
            return response()->json([
                'status' => 200,
                'All orders' =>$Order ]);

        }else{
            return response()->json([
                'status' => 200,
                'message' => $Order ]);}
    }

    public function CancelledOrderList()
    {
        $matchQuery=['status'=>'cancelled'];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => $OrderItems]);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Picked Orders']);}
    }

    public function OrderTracking(Request $request)
    {
        $matchQuery=['status'=>$request->hasAny('status')];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => $OrderItems]);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Picked Orders']);}
    }
}
