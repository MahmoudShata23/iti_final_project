<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{

    public function CategoryView()
    {
        $category = Category::all();
        return response()->json([
        'status'=>200,
        'category'=>$category

        ]);
    }

    public function CategoryStore(Request $request)
    {
        $validator = $request->validate([
            'name'=>'required',
            'descripition'=>'required',
            'image'=>'required|max:2048|image|mimes:png,jpg,jpeg',
        ]);

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $extension = $file->getClientOriginalExtension();

            $fileName = time() . '.' . $extension;

            $file->move('uploads/category/', $fileName);

            $validator['image'] = $fileName;


            $op = Category::create($validator);

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

    public function CategoryEdit($id)
    {
        $category = Category::find($id);

        if($category){
            return response()->json([
                'status'=>200,
                'category'=>$category ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No $category id found' ]);
        }
    }

    public function CategoryUpdate(Request $request, $id)
    {
        $validator = $request->validate([
            'name'=>'required',
            'descripition'=>'required',
          //  'image'=>'required|max:2048|image|mimes:png,jpg,jpeg',
        ]);

        $op = Category::where('id',$request->id)->update($validator);
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
    public function CategoryDelete($id)
    {
        $category = Category::find($id);

        if($category){
            $category->delete();
            return response()->json([
                'status'=>200,
                'message'=>'category deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'category ID not found']);
        }
    }
}
