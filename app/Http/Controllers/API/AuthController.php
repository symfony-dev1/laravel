<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\Cart;


class AuthController extends BaseController
{

    //
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "first_name" => "required",
                "last_name" => "required",
                "email" => "required|email",
                "phone" => "required|numeric|min:10",
                "password" => "required",
                "cpassword" => "required|same:password",
            ]);

            if ($validator->fails()) {
                return $this->sendResponse(false, 'All fields are required', $validator->errors());
            } else {
                if (User::where("email", $request->email)->first()) {
                    return $this->sendResponse(false, 'User Already Exists.');
                } else {
                    $input = $request->all();
                    $input["password"] = Hash::make($input["password"]);
                    $user = User::create($input);
                    return $this->sendResponse(true, 'User Registerd Successfully.', new UserResource($user));
                }
            }
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "email" => "required|email",
                "password" => "required"
            ]);
            if ($validator->fails()) {
                return $this->sendResponse(false, 'All fields are required', $validator->errors());
            }
            if ($request->email != "" && $request->password != "") {
                if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {

                    $user = User::where("email", $request->email)->first();
                    Auth::login($user);
                    // switch cart data to logged in user from cookie
                    if ($request->hasCookie("_cg_id")) {
                        Cart::where("guest_id", $request->cookie("_cg_id"))->update(["user_id" => $user->id, "guest_id" => null]);
                    }


                    return $this->sendResponse(true, 'Login Successfully.', new AuthResource($user));
                } else {
                    return $this->sendResponse(false, 'Credentials dose not match with our system');
                }
            }
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }
    public function logout()
    {
        try {
            Auth::user()->currentAccessToken()->delete();
            return $this->sendResponse(true, 'Logout Successfully.');
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }
    public function getUser()
    {
        try {
            return $this->sendResponse(true, 'User retrived successfully.', new UserResource(Auth::user()));
        } catch (\Exception $e) {
            return $this->sendResponse(false, 'Exception Error: ' . $e->getMessage(), [], 401);
        }
    }
}
