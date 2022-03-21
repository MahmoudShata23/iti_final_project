<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    //Admin view all contact us submissions
    public function viewAllContactUs()
    {
        $ContactUs = ContactUs::all();

        if ($ContactUs) {
            return response()->json([

                'status' => 200,
                'ALLContactUs' => $ContactUs
            ]);
        } else {

            return response()->json([
                'status' => 404,
                'message' => "No ContactUs found"
            ]);
        }
    }

    //Admin view specific contact us submission
    public function showSubmission($id)
    {
        $ContactUs = ContactUs::find($id);

        if ($ContactUs) {
            return response()->json([

                'status' => 200,
                'ContactUs' => $ContactUs
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No ContactUs id found'
            ]);
        }
    }
    //Admin delete specific contact us submission
    public function deleteSubmission($id)
    {
        $ContactUs = ContactUs::find($id);

        if ($ContactUs) {
            $ContactUs->delete();
            return response()->json([
                'status' => 200,
                'message' => 'ContactUs deleted succesfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'ContactUs ID not found'
            ]);
        }
    }


    //User add contact us submission
    public function addContactSubmission(Request $request)
    {
        $validator = $request->validate([
            'fname' => 'required|min:3',
            'lname' => 'required|min:3',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $op = ContactUs::create($validator);

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
