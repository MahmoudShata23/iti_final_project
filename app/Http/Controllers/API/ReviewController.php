<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function StoreReview(Request $request)
    {
        $validator = $request->validate([
            'user_id'=>'required',
            'product_id'=>'required',
            'comment'=>'required|min:3'
        ]);
        $op = Review::create($validator);
        if ($op) {
            return response()->json([
                'status' => 200,
                'message' => 'Review added succesfully',
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    }

    public function PendingReview()
    {
        $matchQuery=['status'=>'0'];
        $review=Review::where($matchQuery)->get();
        if($review){

            return response()->json([
                'status'=>200,
                'message'=>'Review Pending succesfully',
                'reviews' => $review]);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Not Review Pending ']);

        }
    }

    public function ApproveReview($id)
    {
        $review=Review::find($id);

        if($review){

            $review->status="1";
            $review->save();
            return response()->json([
                'status'=>200,
                'message'=>'Review approved succesfully',
                'reviews' => $review]);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Not Review approved ']);

        }
    }

    public function AllReviewsApproved()
    {
        $matchQuery=['status'=>'1'];
        $reviews=Review::where($matchQuery)->get();   //where('id',request->id)
        if($reviews){
            return response()->json([
                'status'=>200,
                'message'=>'Review deleted succesfully',
                'reviews' => $reviews]);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Not aproved  Review  found']);

        }

    }

    public function DeleteReview($id)
    {
        $Review = Review::find($id);

        if($Review){
            $Review->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Review deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Review ID not found']);

    }
    }

    public function GetProductReviews($product_id){

        $matchQuery=['product_id'=>$product_id,'status'=>"1"];
        $reviews=Review::where($matchQuery)->get();
        if($reviews){
            return response()->json([
                'status'=>200,
                'message'=>'Review deleted succesfully',
                'reviews' => $reviews]);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Not aproved  Review  found']);

        }

    }

}
