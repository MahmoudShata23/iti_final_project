<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Crypt;

class AdminProfileController extends Controller
{

    public function AdminProfile($id)
    {
        $admin = Admin::find($id);

        if($admin){
            return response()->json([
                'status'=>200,
                'admin'=>$admin ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No Admin id found' ]);
        }
    }
    public function AdminCreateProfile(Request $request)
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
        //ADD Image

            $op = Admin::create($validator);

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


    public function EditAdminProfile($id)
    {
        $admin = Admin::find($id);

        if($admin){
            return response()->json([
                'status'=>200,
                'admin'=>$admin ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No Admin id found' ]);
        }

    }

    public function UpdateAdminProfile(Request $request, $id)
    {
        $validator = $request->validate([
            'name'=>'required',
            'email'=>'required',
            'address'=>'required',
            'city'=>'required',
            'phone'=>'required',
            'region' =>'required'
        ]);

        $op = Admin::where('id',$request->id)->update($validator);
        if ($op){
            return response()->json([
                'status' => 200,
                'message' => 'Admin updated succesfully',
            ]);
        }else{
            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages()
            ]);
        }

    }


    public function AdminChangePassword($id)
    {
        $admin = Admin::find($id);
       // $decrypt= Crypt::decrypt($admin->password);
        if($admin){
            return response()->json([
                'status'=>200,
                'password'=>$admin->password ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=>'No Admin id found' ]);
        }
    }

    public function AdminUpdatePassword(Request $request,$id)
    {
        $validator = $request->validate([
            'password'=>'required',
        ]);
        $admin = Admin::find($id);
        if ($admin) {
            $admin->password = $request->input('password');
            $admin->save();

            return response()->json([
                'status' => 200,
                'message' => 'Admin password updated succesfully'
            ]);
        }
        else{
                return response()->json([
                    'status'=>404,
                    'message'=> $validator->messages()
                ]);
            }
    }
    public function AllUsers(){
        $admin = User::all();

        if($admin){
            return response()->json([
                'status'=>200,
                'ALL users ' => $admin ]);
        }
        else {
            return response()->json([
                'status'=>404,
                'message'=> "No users found"
            ]);
        }
    }


    public function destoryAdminProfile($id)
    {
        $admin = Admin::find($id);

        if($admin){
            $admin->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Admin deleted succesfully']);
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Admin ID not found']);
        }
    }
}
