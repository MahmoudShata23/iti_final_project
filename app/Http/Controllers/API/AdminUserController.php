<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class AdminUserController extends Controller
{

    public function AllAdminRole()
    {
        $user = User::all();

        return response()->json([
            'status' => 200,
            'user' => $user,
        ]);
    }

    public function StoreAdminUser(Request $request )
    {
        $validator = $request->validate([
            'name'=>'required',
            'email'=>'required',
            'address'=>'required',
            'city'=>'required',
            'phone'=>'required',
            'password'=>'required',
            'region' =>'required'
        ]);
        $validator['password']=bcrypt($validator['password']);

        $validator['profile_photo_path']=('image name to svae');
        $op = User::create($validator);

        if ($op){
            return response()->json([
                'status' => 200,
                'message' => 'success',
            ]);
        }else {
            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages()
            ]);
        }
    }



    public function EditAdminUser($id)
    {
        $getUser = User::find($id);

        if($getUser){
            return response()->json([
                'status'=>200,
                'User'=>$getUser ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No User id found' ]);
        }
    }

    public function UpdateAdmin(Request $request, $id)
    {
        $validator = $request->validate([
            'name'=>'required',
            'email'=>'required',
            'address'=>'required',
            'city'=>'required',
            'phone'=>'required',
            'region' =>'required'
        ]);

        $op = User::where('id',$request->id)->update($validator);
        if ($op){
            return response()->json([
                'status' => 200,
                'message' => 'User updated succesfully',
            ]);
        }else{
            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages()
            ]);
        }
    }

    public function DeleteAdmin($id)
    {
        $getuser = User::find($id);

        if($getuser){
            $getuser->delete();

            return response()->json([
                'status'=>200,
                'message'=>'User deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'User ID not found']);
        }
    }
}
