<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\user;
use App\Http\Requests;
use Session;
use DB;


class RewardsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id =Auth::user()->id;
        $user = user::where('id',$user_id)->select('name')->first();
        session()->push('username', $user->name);
        return view('home');
    }

    public function AddCredits(Requests $request){

        
                dd('addcredits');
        
        
    }




}
