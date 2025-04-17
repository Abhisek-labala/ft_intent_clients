<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Str;
use Carbon\Carbon;
 

class ClientLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('client.clientlogin');
        }
    public function showregisterform()
    {
        return view('client.clientregister');
        }

        public function login(Request $request)
        {
            // Validate the login credentials
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);
        
            $email = strtolower($request->input('email'));
            $password = $request->input('password');
        
            // Attempt to authenticate the user
            $credentials = [
                'email' => $email,
                'password' => $password,
            ];
        
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
        
                // Retrieve the authenticated user
                $user = Auth::user();
        
                // Check if the user's account is active
                if (!$user->is_active) {
                    // If the account is under verification, return a specific response
                    return response()->json([
                        'message' => 'Your account is currently under verification. Please try logging in after some time or contact us at support@flowtransact.com if you have any questions.',
                    ], 403);
                }
        
                // Determine the redirect route based on the user's role
                $redirectRoute = route('client.dashboard');
        
                return response()->json([
                    'message' => 'Login successful',
                    'redirect' => $redirectRoute,
                ], 200);
            }
        
            // Authentication failed, return with errors
            return response()->json([
                'message' => 'The provided email and password do not match with our records.',
            ], 401);
        }
        

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Change to your clients table if needed
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $email = strtolower($request->input('email'));
        $username = Str::slug($request->name);
        $originalUsername = $username;
        $randomNumber = mt_rand(1000, 9999);
        $username = $originalUsername . $randomNumber;
        while (User::where('username', $username)->exists()) {
            $randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number
            $username = $originalUsername . $randomNumber;
        }
        $client_secret = Str::random(32);

        // Create a new user/client
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'username' => $username,
            'client_secret'=>$client_secret,
            'role'=>'client'
        ]);
        $user = User::find($user->id);
        $client_id = $user->client_id;

        // Respond with user data and token
        return response()->json([
            'API_ID' =>$client_id,
            'API_KEY'=>$client_secret,
            // 'token' => $token,
        ], 201);
    }
}