<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSignup;
use Illuminate\Support\Facades\Validator;

class UserSignupController extends Controller
{
    public function account(Request $request)
    {
        // Validation rules for the account step
        $rules = [
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Create a new user record
        $user = new User;
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        // Store the user ID in the session for later steps
        session(['user_id' => $user->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully',
            'user_id'=>$user->id
        ]);
    }

    public function personal(Request $request)
    {
        // Validation rules for the personal step
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Create a new user signup record
        $signup = new UserSignup;
        $signup->first_name = $request->input('first_name');
        $signup->last_name = $request->input('last_name');
        $signup->contact_no = $request->input('contact_no');
        $signup->alternate_contact_no = $request->input('alternate_contact_no');
        $signup->user_id = $request->input('user_id');
        $signup->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Personal details saved successfully',
            'user_id'=>session('user_id')
        ]);
    }

    public function image(Request $request)
    {
        // Validation rules for the image step
        $rules = [
            'image1' => 'image',
            'image2' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Update the user signup record with the images
        $signup = UserSignup::where('user_id', $request->input('user_id'))->first();
        if ($request->hasFile('image1')) {
            $signup->image1 = $request->file('image1')->store('');
        }
        if ($request->hasFile('image2')) {
            $signup->image2 = $request->file('image2')->store('');
        }
        $signup->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Images saved successfully',
            'user_id'=>session('user_id')
        ]);
    }

    public function finish(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => ''
        ]);
    }
}
