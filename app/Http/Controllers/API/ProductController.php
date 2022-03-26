<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Facade;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\MultiImage;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();

        return response()->json([
            'status' => 200,
            'products' => $product,
        ]);
    }
    public function StoreProduct(Request $request)
    {
        $validator = $request->validate([
            'brand' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required|max:191',
            'description' => 'required|max:191',
            'product_qty' => 'required|max:20',
            'tags' => 'required|max:20',
            'product_size' => 'required|max:191',
            'color' => 'required|max:191',
            'selling_price' => 'required|max:20',
            'discount_price' => 'required|max:20',
            'short_desc' => 'required|max:191',
            'status' => 'required',
            'avgRate' => 'required',
            'image' => 'required|max:2048|image|mimes:png,jpg,jpeg',

        ]);
        if ($image = $request->image) {
            $destinationPath = 'uploads/product';
            $newImage = time() . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $newImage);
            $validator['image'] = $newImage;
        }
        $op = Product::create($validator);
        if ($op) {
            $product = Product::latest('id')->first();
            $this->send_product_update($product);
            return response()->json([
                'status' => 200,
                'message' => 'Product added succesfully',
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }
    }
    public function send_product_update(Product $product)
    {
        $data = array('id' => $product->id, 'name' => $product->name, 'description' => $product->description, 'price' => $this->calculatePrice($product));
        $subscriptions = Subscription::all();
        if ($subscriptions) {
            $users = array();
            foreach ($subscriptions as $subscription) {
                array_push($users, $subscription->email);
            }
            Mail::send('productmail', $data, function ($message) use ($product, $users) {
                $message->bcc($users)->subject('New Product Added');
                $message->attach(public_path('/uploads/product/' . $product->image));
                $message->from('mshata176@gmail.com', 'Furniture Store');
            });
        }
    }

    public function calculatePrice(Product $product)
    {
        if ($product->discount_price) {
            return intval($product->selling_price) - intval($product->discount_price);
        } else {
            return intval($product->selling_price);
        }
    }
    public function MultiImageUpdate(Request $request)
    {
    }
    public function ShowProduct($id)
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
    public function EditProduct($id)
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
    public function UpdateProduct(Request $request, $id)
    {
        $validator = $request->validate([

            'brand' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'product_qty' => 'required',
            'tags' => 'required',
            'product_size' => 'required',
            'color' => 'required',
            'selling_price' => 'required',
            'discount_price' => 'required',
            'short_desc' => 'required',
            'status' => 'required',
            'avgRate' => 'required'

        ]);
        $op = Product::where('id', $request->id)->update($validator);
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
    public function DeleteProduct($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            //unlink($product->image);
            return response()->json([
                'status' => 200,
                'message' => '$product deleted succesfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => '$product ID not found'
            ]);
        }
    }
    // product Stock
    public function ProductStock()
    {
        $products = Product::latest()->get();
        if ($products) {
            return response()->json([
                'status' => 200,
                'message' => 'Product get succesfully',
                'products' => $products
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'errors' => 'not product update found'
            ]);
        }
    }
}
