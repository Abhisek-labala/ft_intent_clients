<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function clientlogout(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Log the user out
        Auth::logout();

        // Flush the session
        $request->session()->flush();

        // Redirect to login page
        return redirect('/')->with('success', 'You have been logged out.');

    }
}
