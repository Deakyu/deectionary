<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Mail;
use App\Mail\verifyEmail;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')
            ->only('logout');
    }

    public function register(Request $request) {
        // input validation
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required|between:6,25|confirmed'
        ]);

        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->verifyToken = str_random(60);
        $user->save();
        // Get the latest saved user
        $thisUser = User::findOrFail($user->id);
        // Send email to the user
        $this->sendEmail($thisUser);

        return response()
            ->json([
                'registered' => true,
            ]);
    }

    public function sendEmail($thisUser) {
        // Send the verifiable email to the user with token
        Mail::to($thisUser['email'])->send(new verifyEmail($thisUser));
    }

    public function sendEmailDone($email, $verifyToken) {
        // From the sent email

        // Get the user with the verify token
        $user = User::where(['email' => $email, 'verifyToken' => $verifyToken])->first();
        if($user) {
            // if user exists with the token, redirect from the email to the home page of the app
            User::where(['email' => $email, 'verifyToken' => $verifyToken])->update(['status' => 1, 'verifyToken' => null]);
            return redirect('/')->with('verified', 'true');
        } else {
            return 'user not found';
        }
    }

    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|between:6,25'
        ]);

        $user = User::where('email', $request->email)->first();

        // user->status == 1 means the user verified the email
        if($user && Hash::check($request->password, $user->password) && $user->status == 1) {
            $user->api_token = str_random(60);
            $user->save();

            return response()
                ->json([
                    'authenticated' => true,
                    'api_token' => $user->api_token,
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
        }

        return response()
            ->json([
                'email' => ['Provided email and password do not match!']
            ], 422);
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->api_token = null;
        $user->save();
        return response()
            ->json([
                'logged_out' => true
            ]);
    }
}
