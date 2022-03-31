<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminProfileController extends Controller
{

    public function AdminProfile($id)
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

    public function SignUp(Request $request)
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
        $op = Admin::create($validator);
        if ($op) {
            return response()->json([
                'status' => 200,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'errors' => 'Admin not  added succesfully',
            ]);
        }
    }

    public function EditAdminProfile($id)
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

    public function UpdateAdminProfile(Request $request, $id)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'region' => 'required'
        ]);

        $op = Admin::where('id', $request->id)->update($validator);
        if ($op) {
            return response()->json([
                'status' => 200,
                'message' => 'Admin updated succesfully',
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'errors' => 'Admin not  updated succesfully',
            ]);
        }
    }

    public function AdminChangePassword($id)
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

    public function AdminUpdatePassword(Request $request, $id)
    {
        if (auth()->guard('admin')->user()) {
            $id = auth()->guard('admin')->user()->id;
        }
        $validator = $request->validate([
            'old_password' => 'required|min:8',
            'password' => 'required|confirmed|min:8',
        ]);
        $admin = Admin::find($id);
        if ($admin) {
            if (Hash::check($request->old_password, $admin->password)) {
                $admin->password = Hash::make($request->password);
                $admin->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Admin password updated succesfully'
                ]);
            } else {
                return ["error" => "credentials is incorrect"];
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Admin password Not updated succesfully'
            ]);
        }
    }
    public function AllUsers()
    {
        $admin = User::all();

        if ($admin) {
            return response()->json([
                'status' => 200,
                'ALL users ' => $admin
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No users found"
            ]);
        }
    }

    public function destoryAdminProfile($id)
    {
        $admin = Admin::find($id);

        if ($admin) {
            $admin->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Admin deleted succesfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Admin ID not found'
            ]);
        }
    }

    public function AdminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Admin::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Admin log in succesfully',
            'user' => $user,
            'access_token' => $user->createToken($request->email)->plainTextToken,
            
        ]);
////
        /*
        try {
            $credentials = $request->only('email', 'password');

            if ($token = $this->guard()->attempt($credentials)) {
                $data =  $this->respondWithToken($token);
            } else {
                return $this->responseError(null, 'Invalid Email and Password !', Response::HTTP_UNAUTHORIZED);
            }

            return $this->responseSuccess($data, 'Logged In Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        */
    }

    public function logout(Request $request)
    {

        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();
        //$request->user->tokens()->delete(); // use this to revoke all tokens (logout from all devices)
        return response()->json(null, 200);
    }
    
    public function getAuthdAdmin(Request $request)
    {
        return $request->user();
    }
}
