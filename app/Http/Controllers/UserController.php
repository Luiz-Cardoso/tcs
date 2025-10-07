<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:4|max:150',
            'username' => 'required|string|min:3|max:20|alpha_num|unique:users,username',
            'password' => 'required|string|min:3|max:20|alpha_num',
            'email' => 'nullable|email',
            'phone' => 'nullable|digits_between:10,14',
            'experience' => 'nullable|string|min:10|max:600',
            'education' => 'nullable|string|min:10|max:600',
        ]);

        if($validator->fails()){
            $details = [];
            foreach($validator->errors()->messages() as $field => $errors){
                foreach($errors as $error){
                    $details[] = ['field' => $field, 'error' => $error];
                }
            }

            return response()->json([
                'message' => 'Validation error',
                'code' => 'UNPROCESSABLE',
                'details' => $details
            ], 422);
        }

        $user = new User();
        $user->name = strtoupper($request->name);
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->experience = $request->experience;
        $user->education = $request->education;
        $user->save();

        return response()->json(['message' => 'Created'], 201);
    }

    public function show(Request $request)
    {
        $jwt = $request->get('jwt_data');

        if(!$jwt){
            return respsonse()->json(['message' => 'Invalid Token'], 401);
        }

        $user = User::find($jwt['sub']); //busca o usuario pelo id do token

        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }

        if($user->id != $jwt['sub']){ 
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'experience' => $user->experience,
            'educaction' => $user->education
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $jwt = $request->get('jwt_data');

        if(!$jwt){
            return response()->json(['message' => 'Invalid Token'], 401);
        }

        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }

        if($user->id != $jwt['sub']){
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'nullable|string|min:4|max:150',
            'email' => 'nullable|email',
            'password' => 'nullable|string|min:3|max:20|alpha_num',
            'phone' => 'nullable|digits_between:10,14',
            'experience' => 'nullable|string|min:10|max:600',
            'education' => 'nullable|string|min:10|max:600',
        ]);

        if($validator->fails()){
            $details = [];
            foreach($validator->errors()->messages() as $field => $errors){
                foreach($errors as $error){
                    $details[] = ['$field' => $field, 'error' => $error];
                }
            }

            return response()->json([
                'message' => 'Validation error',
                'code' => 'UNPROCESSABLE',
                'details' => $details
            ], 422);
        }

        if($request->has('name')) $user->name = strtoupper($request->name) ?: null;
        if($request->has('email')) $user->email = $request->email ?: null;
        if($request->has('password')) $user->password = $request->password ? \Hash::make($request->password) : null;
        if($request->has('phone')) $user->phone = $request->phone ?: null;
        if($request->has('experience')) $user->experience = $request->experience ?: null;
        if($request->has('education')) $user->education = $request->education ?: null;

        $user->save();

        return response()->json(['message' => 'Update'], 200);
    }

    public function destroy(Request $request, $id)
    {
        $jwt = $request->get('jwt_data');

        if(!$jwt){
            return response()->json(['message' => 'Invalid Token'], 401);
        }

        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }

        if(!$user->id != $jwt['sub']){
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted sucessfully'], 200);
    }
}
