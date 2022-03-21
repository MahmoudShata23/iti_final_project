<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;


class SliderController extends Controller
{
    public function ViewSlider()
    {
        $Slider = Slider::all();
        return response()->json([
            'status'=>200,
            'Slider'=>$Slider

        ]);
    }

    public function StoreSlider(Request $request)
    {
        $validator = $request->validate([
            'name'=>'required',
            'description'=>'required',
            'image'=>'required|max:2048|image|mimes:png,jpg,jpeg',
        ]);

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $extension = $file->getClientOriginalExtension();

            $fileName = time() . '.' . $extension;

            $file->move('uploads/slider/', $fileName);

            $validator['image'] = $fileName;


            $op = Slider::create($validator);

            if ($op) {
                return response()->json([
                    'status' => 200,
                    'message' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ]);
            }
        }
    }

    public function EditSlider($id)
    {
        $Slider = Slider::find($id);

        if($Slider){
            return response()->json([
                'status'=>200,
                'Slider'=>$Slider ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No Slider id found' ]);
        }
    }

    public function UpdateSlider(Request $request, $id)
    {
        $validator = $request->validate([
            'name'=>'required',
            'descripition'=>'required',
            //  'image'=>'required|max:2048|image|mimes:png,jpg,jpeg',
        ]);

        $op = Slider::where('id',$request->id)->update($validator);
        if ($op){
            return response()->json([
                'status' => 200,
                'message' => 'Slider updated succesfully',
            ]);
        }else{
            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages()
            ]);
        }
    }

    public function DeleteSlider($id)
    {
        $Slider = Slider::find($id);

        if($Slider){
            $Slider->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Slider deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Slider ID not found']);
        }

    }
}
