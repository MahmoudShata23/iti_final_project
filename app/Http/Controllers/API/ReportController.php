<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DateTime;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function ViewReport()
    {
        $orders = Order::where('return_order', '1')->orderBy('id','DESC')->get();
        if($orders){
            return response()->json([
                'status'=>200,
                '$product'=>$orders ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No order id found' ]);
        }
    }

    public function ReportSearchByDate(Request $request)
    {
        $date = new DateTime($request->date);
        $formatDate = $date->format('d F Y');


        $orders = Order::where('order_date', $formatDate)->latest()->get();
        if($orders){
            return response()->json([
                'status'=>200,
                '$product'=>$orders ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No order id found' ]);
        }
    }

    public function ReportSearchByMonth(Request $request)
    {
        $orders = Order::where('order_month', $request->month)->where('order_year', $request->year_name)->latest()->get();
        if($orders){
            return response()->json([
                'status'=>200,
                '$product'=>$orders ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No order id found' ]);
        }
    }
    public function ReportSearchByYear(Request $request)
    {
        $orders = Order::where('order_year', $request->year)->latest()->get();
        if($orders){
            return response()->json([
                'status'=>200,
                '$product'=>$orders ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No order id found' ]);
        }
    }

}
