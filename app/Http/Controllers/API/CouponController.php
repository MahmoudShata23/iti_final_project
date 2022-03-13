<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function ViewCoupon()
    {
        $Coupon = Coupon::all();
        return response()->json([
            'status'=>200,
            'Coupon'=>$Coupon

        ]);
    }

    public function StoreCoupon(Request $request)
    {
        $validator = $request->validate([
            'name'=>'required|max:191',
            'discount'=>'required',
            'validity'=>'required',
            'status'  =>'required'
        ]);
        $op = Coupon::create($validator);

        if ($op) {
            return response()->json([
                'status' => 200,
                'message' => 'Coupon added succesfully',
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }

    }

    public function EditCoupon($id)
    {
        $Coupon = Coupon::find($id);

        if($Coupon){
            return response()->json([
                'status'=>200,
                'Coupon'=>$Coupon ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No Coupon id found' ]);
        }

    }

    public function UpdateCoupon(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
            'discount'=>'required',
            'validity'=>'required',
            'status'  =>'required'
        ]);

        $op = Coupon::where('id',$request->id)->update($validator);
        if ($op){
            return response()->json([
                'status' => 200,
                'message' => 'Coupon updated succesfully',
            ]);
        }else{
            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages()
            ]);
        }
    }

    public function DeleteCoupon($id)
    {
        $Coupon = Coupon::find($id);

        if($Coupon){
            $Coupon->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Coupon deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Coupon ID not found']);
        }
    }

}
