<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\CategoryProductsEn;
use App\Http\Resources\CategoryProductsAr;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Rules\UserPasswordRule;
use DB;

class IndexController extends Controller
{
    public function index($id)
    {
        $admin = Admin::find($id);

        if ($admin) {
            return response()->json([
                'status' => 200,
                'admin' => $admin
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Admin id found'
            ]);
        }
    }

    public function UserLogout()
    {
    }

    public function UserProfile($id)
    {
        $User = User::find($id);

        if ($User) {
            return response()->json([
                'status' => 200,
                'User' => $User
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No User id found'
            ]);
        }
    }

    public function UserProfileUpdate($id,Request $request)
    {
              
            $validator = $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|email',
                'address' => 'required',
                'city' => 'required',
                'phone' => 'required|regex:/(01)[0-9]{9}/',
                'region' => 'required'
            ]);
            $matchQuery=['id'=>$id];
            $user=User::where($matchQuery)->first();
            if($user->email!=$request->email){
                $matchQuery=['email'=>$request->email];
                $user=User::where($matchQuery)->first();
                if($user){
                    return response()->json([
                        'status'=>422,
                        'message'=>'Email Already Exists'
                    ]);
                }
            }

            $op = User::where('id', $id)->update($validator);
            if ($op) {
                return response()->json([
                    'status' => 200,
                    'message' => 'User updated succesfully',
                ]);
            } else {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ]);
            }
    }

    public function profileUpdateValidation($email, $request)
    {
        if ($email == $request->email) {
            $request->validate([
                'name' => 'min:3|required',
                'email' => 'email|required',
                'phone' => 'numeric|required',
                'address' => 'min:5|required',
                'city' => 'min:3|required',
                'region' => 'min:3|required'
            ]);
        } else {

            $request->validate([
                'name' => 'min:3|required',
                'email' => 'email|required|unique:users',
                'phone' => 'numeric|required',
                'address' => 'min:5|required',
                'city' => 'min:3|required',
                'region' => 'min:3|required'
            ]);
        }
    }

    public function UserPassword($id)
    {
        $admin = Admin::find($id);
        // $decrypt= Crypt::decrypt($admin->password);
        if ($admin) {
            return response()->json([
                'status' => 200,
                'password' => $admin->password
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Admin id found'
            ]);
        }
    }

    public function UserPasswordUpdate(Request $request, $id)
    {
        $validator = $request->validate([
            'password' => 'required',
        ]);
        $getUser = User::find($id);
        if ($getUser) {
            $getUser->password = $request->input('password');
            $getUser->save();

            return response()->json([
                'status' => 200,
                'message' => 'User password updated succesfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => $validator->messages()
            ]);
        }
    }

    public function DetailsProduct($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json([
                'status' => 200,
                '$product' => $product
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No product id found'
            ]);
        }
    }

    public function subCatProduct($subcat_id)
    {

        $products = DB::table('products')->where('sub_category_id', $subcat_id)->get();
        if ($products) {

            return response()->json([
                'status' => 200,
                'message' => $products
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'products ID not found'
            ]);
        }
    }

    public function CatProduct($cat_id)
    {

        $products = DB::table('products')->where('category_id', $cat_id)->get();
        if ($products) {
            return response()->json([
                'status' => 200,
                'message' => $products
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'products ID not found'
            ]);
        }
    }

    public function ProductSearchByName($product_name)
    {
        $products = DB::table('products')->where('name', 'like', '%' . $product_name . '%')->get();
        if ($products) {
            return response()->json([
                'status' => 200,
                'message' => $products
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'products ID not found'
            ]);
        }
    }

    public function ProductSearchByColor($product_color)
    {

        $products = DB::table('products')->where('color', 'like', '%' . $product_color . '%')->get();
        if ($products) {
            return response()->json([
                'status' => 200,
                'message' => $products
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'products ID not found'
            ]);
        }
    }
    public function ProductSearchByCategory($product_category)
    {
        $categories = Category::where('name', 'like', '%' . $product_category . '%')->get();
        if ($categories) {
            return response()->json([
                'status' => 200,
                'message' => $categories
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'products ID not found'
            ]);
        }
    }

    public function ProductSearchByPrice($max_product_price)
    {
        $products = Product::where('selling_price', '<=', $max_product_price)->get();
        if ($products) {
            return response()->json([
                'status' => 200,
                'message' => $products
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'products ID not found'
            ]);
        }
    }
}
