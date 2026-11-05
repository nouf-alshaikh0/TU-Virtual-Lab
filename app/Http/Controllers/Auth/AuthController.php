<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
//use App\Http\Requests\web\ForgotPassword;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.guest_page');
    }
    public function login()
    {
        return view('auth.login_page');
    }
    public function userLogin(Request $request)
    {
        $request->validate([
            'email'     => ['required', 'string', 'email', 'min:6', 'max:255'],
            'password'      => ['required', 'string', 'min:8'],
        ]);

        if (Auth()->attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            return redirect('dashboard');
        }
        Session::flash('message', 'Invalid User Name or Password');
        return redirect()->back();
    }

    public function forgotPassword(){
        return view('auth.forgot_password_page');
    }

    public function passwordLink(Request $request)
    {
        $request->validate([
            'email'     => ['required', 'string', 'email', 'min:6', 'max:255'],
        ]);
        $user = User::where('email',$request->email)->first();
        if($user){
            try {

                $token = Str::random(64);
                
                DB::table('password_resets')
                        ->updateOrInsert(
                            ['email' => $request->email],
                            ['token' => $token, 'created_at' => Carbon::now()]
                        );
    
                Mail::send('auth.emails.send_forgot_password', ['token' => $token], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Reset Password');
                });
                Session::flash('success_message', ' Reset Link Send Successfully!');
    
                return redirect()->back();
            } catch (\Exception $e) {
                Session::flash('message', 'Invalid Data');
    
                return redirect()->back();
            }
        }

        Session::flash('message', 'Invalid Email ');
        return redirect()->back();
    }

    public function resetPassword($token)
    {
        $data = \DB::table('password_resets')->where('token', $token)->first();

        if($data){
            $email = $data->email;
            return view('auth.reset_password_page', ['token' => $token,'email'=>$email]);
        }
        return redirect('/forgot-password')->with('message', 'Invalid token!');
    }

    public function submitResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' => $request->email,
                    'token' => $request->token,
                ])
                ->first();

            if (! $updatePassword) {
                return back()->withInput()->with('message', 'Invalid token!');
            }

            $user = User::query()->where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $request->email])->delete();
          
            return redirect('/login')->with('success_message', 'Your password has been changed!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Invalid Data!');

        }
    }
    
    public function logout()
    {
        Session::invalidate();
        Session::regenerateToken();
        Auth::logout();
        Session::flash('success_message', 'You have been successfully logged out.');
        return redirect('/');
    }
}
