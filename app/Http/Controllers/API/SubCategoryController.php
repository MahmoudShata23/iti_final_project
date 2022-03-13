<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{

    public function SubCategoryView()
    {
        $Subcategory = SubCategory::all();
        return response()->json([
            'status'=>200,
            'category'=>$Subcategory

        ]);
    }

    public function SubCategoryStore(Request $request)
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

            $file->move('uploads/subcategory/', $fileName);

            $validator['image'] = $fileName;


            $op = SubCategory::create($validator);

            if ($op) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Subcategory added succesfully',
                ]);
            } else {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ]);
            }
        }
    }

    public function SubCategoryshow($id)
    {
        $SubCategory = SubCategory::find($id);

        if($SubCategory){
            return response()->json([
                'status'=>200,
                'admin'=>$SubCategory ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No SubCategory id found' ]);
        }
    }

    public function SubCategoryEdit($id)
    {
        $Subcategory = SubCategory::find($id);

        if($Subcategory){
            return response()->json([
                'status'=>200,
                'Subcategory'=>$Subcategory ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No Subcategory id found' ]);
        }
    }

    public function SubCategoryUpdate(Request $request, $id)
    {
        $validator = $request->validate([
            'name'=>'required',
            'description'=>'required',
            //  'image'=>'required|max:2048|image|mimes:png,jpg,jpeg',
        ]);

        $op = SubCategory::where('id',$request->id)->update($validator);
        if ($op){
            return response()->json([
                'status' => 200,
                'message' => 'SubCategory updated succesfully',
            ]);
        }else{
            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages()
            ]);
        }
    }

    public function SubCategoryDelete($id)
    {
        $Subcategory = SubCategory::find($id);

        if($Subcategory){
            $Subcategory->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Subcategory deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Subcategory ID not found']);
        }
    }



}
