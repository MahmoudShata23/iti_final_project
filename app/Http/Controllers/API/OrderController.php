<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use  App\Models\Order;
use  App\Models\OrderItem;
use  App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class OrderController extends Controller
{
    // Pending Orders
    public function OrdersDetails($order_id)
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

    public function PendingOrders()
    {

        $matchQuery=['status'=>'Pending'];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already added to PendingOrders' ]);

        }else{
                return response()->json([
                    'status' => 200,
                    'message' => 'OrderItems not PendingOrders']);}
    }

    public function ProcessingOrders()
    {
        $matchQuery=['status'=>'Processing'];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already added to PendingOrders' ]);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not PendingOrders']);}
    }

    public function ConfirmedOrders()
    {
        $matchQuery=['status'=>'Confirmed'];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already added to PendingOrders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not PendingOrders']);}
    }

    public function ConfirmedOrdersDetails($order_id)
    {
        $order = Order::with('user')->where('id', $order_id)->first();
        $order_item = OrderItem::with('product')->where('order_id', $order_id)->
            orderBy('id', 'DESC')->get();

        if($order_item){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already Confirmed Orders Details']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Confirmed Orders Details']);}
    }

    public function PickedOrders()
    {
        $matchQuery=['status'=>'picked'];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already added to Picked Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Picked Orders']);}
    }

    public function ShippedOrders()
    {
        $matchQuery=['status'=>'shipped'];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already Shipped Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Shipped Orders']);}
    }

    public function DeliveredOrders()
    {
        $matchQuery=['status'=>'delivered'];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already delivered Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not delivered Orders']);}

    }

    public function CanceledOrders()
    {
        $matchQuery=['status'=>'canceled'];
        $OrderItems=Order::where($matchQuery)->orderBy('id', 'DESC')->get();
        if($OrderItems){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already canceled Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not canceled Orders']);}
    }

    public function PendingToConfirm($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->update([
            'status' => 'Confirmed',
        ]);
        if($order){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already Confirmed Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Confirmed Orders']);}

    }

    public function ConfirmedToProcessing($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->update([
            'status' => 'Processed',
        ]);
        if($order){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already Processed to Confirmed Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Processed to Confirmed Orders']);}
    }

    public function ProcessingToPicked($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->update([
            'status' => 'Picked',
        ]);
        if($order){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already Processing To Picked Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Processing To Picked Orders']);}
    }

    public function PickedToShipped($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->update([
            'status' => 'Shipped',
        ]);
        if($order){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already Processing To Shipped Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Processing To Shipped Orders']);}
    }

    public function ShippedToDelivered($order_id)
    {
        $product = OrderItem::where('order_id', $order_id)->get();

        foreach($product as $item)
        {
            Product::where('id', $item->id)->update([
                'product_qty' => DB::raw('product_qty-'. $item->qty),
            ]);
        }
        $order = Order::findOrFail($order_id);
        $order->update([
            'status' => 'Delivered',
        ]);

        if($order){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already Processing To delivered Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Processing To delivered Orders']);}
    }

    public function DeliveredToCanceled($order_id)
    {
        $product = OrderItem::where('order_id', $order_id)->get();

        foreach($product as $item)
        {
            Product::where('id', $item->id)->update([
                'product_qty' => DB::raw('product_qty-'. $item->qty),
            ]);
        }
        $order = Order::findOrFail($order_id);
        $order->update([
            'status' => 'canceled',
        ]);

        if($order){
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems already Processing To canceled Orders']);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'OrderItems not Processing To canceled Orders']);}
    }

}
