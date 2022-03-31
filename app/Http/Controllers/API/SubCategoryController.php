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

    public function Category_Subcategories($category_id){
        $matchQuery=['category_id'=>$category_id];

        $subcategories=SubCategory::where($matchQuery)->get();

        if($subcategories){
            return response()->json([
                'status'=>200,
                'subcategories'=>$subcategories
            ]);
        }else{
            return response()->json([
                'status'=>422,
                'message'=>'no subcategories found'
            ]);
        }
    }

    public function SubCategoryStore(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'category_id'=>'required|numeric',
            'image'=>'required|max:2048|image|mimes:png,jpg,jpeg',
        ]);

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $extension = $file->getClientOriginalExtension();

            $fileName = time() . '.' . $extension;

            $file->move('uploads/subcategory/', $fileName);

            $subcategory=new SubCategory;
            $subcategory->name=$request->name;
            $subcategory->description=$request->description;
            $subcategory->category_id=intval($request->category_id);
            $subcategory->image = $fileName;
            $subcategory->save();

            return response()->json([
                'status' => 200,
                'message' => 'success',
            ]);
            
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
                'message' => 'success',
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
