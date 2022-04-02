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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

    public function UserProfileUpdate($id, Request $request)
    {

        $validator = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required|regex:/(01)[0-9]{9}/',
            'region' => 'required'
        ]);
        $matchQuery = ['id' => $id];
        $user = User::where($matchQuery)->first();
        if ($user->email != $request->email) {
            $matchQuery = ['email' => $request->email];
            $user = User::where($matchQuery)->first();
            if ($user) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Email Already Exists'
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
    public function signup(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'region' => 'required'
        ]);
        $validator['password'] = bcrypt($validator['password']);
        $op = User::create($validator);
        if ($op) {
            return response()->json([
                'status' => 200,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'errors' => 'users not  added succesfully',
            ]);
        }
    }
    public function destoryAdminProfile($id)
    {
        $users = User::find($id);

        if ($users) {
            $users->delete();
            return response()->json([
                'status' => 200,
                'message' => 'users deleted succesfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'users ID not found'
            ]);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        /*
        $today = date("F j, Y, g:i a");           // March 10, 2001, 5:16 pm
$today = date("m.d.y");                           // 03.10.01
$today = date("j, n, Y");                         // 10, 3, 2001
$today = date("Ymd");                             // 20010310
$today = date('h-i-s, j-m-y, it is w Day');       // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
$today = date('\i\t \i\s \t\h\e jS \d\a\y.');     // It is the 10th day (10ème jour du mois).
$today = date("D M j G:i:s T Y");                 // Sat Mar 10 17:16:18 MST 2001
$today = date('H:m:s \m \e\s\t\ \l\e\ \m\o\i\s'); // 17:03:18 m est le mois
$today = date("H:i:s");                           // 17:16:18
$today = date("Y-m-d H:i:s");                     // 2001-03-10 17:16:18 (le format DATETIME de MySQL)
        */
        return response()->json([
            'status' => 200,
            'message' => 'users log in succesfully',
            'user' => $user,
            'access_token' => $user->createToken($request->email)->plainTextToken,

        ]);
    }
    public function logout(Request $request)
    {

        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();
        //$request->user->tokens()->delete(); // use this to revoke all tokens (logout from all devices)
        return response()->json([
            'status' => 200,
            'message' => 'users logout succesfully',

        ]);
    }
    public function getAuthdAdmin(Request $request)
    {
        return $request->user();
    }
}
