<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\laravel_notifications;
use App\laravel_notifications_messages;
use App\user;
use App\Http\Requests;
use Session;
use DB;
use Socialite;
use App\rewards;
use App\user_credits;
use Carbon;
use Excel;

class HomeController extends Controller
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
        $data = user::get()->toArray();
        
        
       // return Excel::create('laraveexecl', function($excel) use ($data) {
       //     $excel->sheet('Sheet1', function($sheet) use ($data)
       //     {
       //          $sheet->cell('A1', function($cell) {$cell->setValue('First Name');   });
       //          $sheet->cell('B1', function($cell) {$cell->setValue('Last Name');   });
       //          $sheet->cell('C1', function($cell) {$cell->setValue('Email');   });
       //          if (!empty($data)) {
       //            foreach ($data as $key => $value) {
       //                  $i= $key+2;
       //                  $sheet->cell('A'.$i, $value['id']); 
       //                  $sheet->cell('B'.$i, $value['name']); 
       //                  $sheet->cell('C'.$i, $value['email']); 
       //              }
       //          }

       //          //$sheet->fromArray($data);
               
       //     });
       //      $excel->sheet('sheet2', function($sheet) use ($data)
       //     {
       //          $sheet->cell('A1', function($cell) {$cell->setValue('First Name');   });
       //          $sheet->cell('B1', function($cell) {$cell->setValue('Last Name');   });
       //          $sheet->cell('C1', function($cell) {$cell->setValue('Email');   });
       //          if (!empty($data)) {
       //            foreach ($data as $key => $value) {
       //                  $i= $key+2;
       //                  $sheet->cell('A'.$i, $value['id']); 
       //                  $sheet->cell('B'.$i, $value['name']); 
       //                  $sheet->cell('C'.$i, $value['email']); 
       //              }
       //          }

       //          //$sheet->fromArray($data);
               
       //     });

       // })->download();

        return view('home');
    }

    public function AddCredits(Request $request){

            // $user_id =Auth::user()->id;
            // $user = user::where('id',$user_id)->select('name')->first();

            // $rewards = new rewards();
            // $rewards->user_id = 4;
            // $rewards->username = 'madhuri';
            // $rewards->original_credits =50;
            // $rewards->current_credits = 50;
            // $rewards->original_credits_added_on = date('Y-m-d H:i:s');
            // $rewards->description = 'description';        
            // $rewards->credit_expiry_date = '2018-03-20';     
            // $rewards->is_expired = 0;      
            // $rewards->created_by = $user->name;   
            // $rewards->updated_by = $user->name;
            // $rewards->save();

            //$users_credits = user_credits::select('id','username','reward_credits')->where('reward_credits','!=',0)->get()->toArray();
           
            $today = Carbon\Carbon::today()->toDateString();
            
            $rewards=rewards::select('*')->where('credit_expiry_date','<',$today)->where('is_expired',0)->get()->toArray();
            //dd($rewards);
            if(!empty($rewards))
            {
                foreach ($rewards as $key => $value) {  
                

                    $users_credit = user_credits::select('id','username','reward_credits')->where('id',$value['user_id'])->where('reward_credits','!=',0)->first(); 
                    
                       if(!empty($users_credit))
                        {
                            $subtract_rewards = max($users_credit->reward_credits - $value['original_credits'],0);

                            $deleted_credits = user_credits::where('username',$users_credit->username)->update(['reward_credits' => max($subtract_rewards,0)]);
                        }    
                        

                   
                    $deleted_rewards = rewards::where('id',$value['id'])->update(['is_expired' => 1]);
                  
                }
            }
           return redirect()->back()->with('success_msg', 'Reward Subtracted');
        
    }


    public function markAsReadUserNotification(Request $request){
        
        $message_id = $request->message_id;
        $user_name = Auth::user()->name;

        if($message_id != null)
        {
            $markNotification = laravel_notifications::where('username', $user_name)->where('message_id',$message_id)->update(['is_read' => 1]);
        }

        return response($markNotification);
       
     
    }

    public function deleteUserNotification(Request $request){

        $message_id = $request->message_id;
        $user_name = Auth::user()->name;
        
         if($message_id != null)
        {
            $deleteNotifications = laravel_notifications::where('username', $user_name)->where('message_id', $message_id)->update(['is_deleted' => 3]);
            
        }
       
        return response($deleteNotifications);

    }
    public function getNotification(Request $request){
        $offset = $request->offset;
        $limit = 5;
        $user_name = Auth::user()->name;
        
        $data = laravel_notifications_messages::select('laravel_notifications_messages.title','laravel_notifications.username','laravel_notifications.message_id','laravel_notifications.is_read')->join('laravel_notifications', 'laravel_notifications.message_id', '=', 'laravel_notifications_messages.id')->where('laravel_notifications.username', '=',$user_name )->where('laravel_notifications.is_deleted','=',2)->skip($offset)->take($limit)->orderBy('laravel_notifications_messages.id')->get()->toArray();

        return json_encode($data);
       
    }
    
    public function loginViaGoogle(Request $request){
        return view('login-with-google');
    }

    
    //************** Callback through Google **************

    public function redirectToProvider(Request $request){
         return Socialite::driver('google')->redirect();
         
    }

    public function responseFromGoogleCallback(Request $request){
        try{
            $user = Socialite::driver('google')->user();
            dd($user);    
        }
        catch(Exception $e)
        {
                return redirect()->back()->with('error_message', 'Error! Something went wrong try to login again.');
        }
        
        
    }

    //************** Callback through Linked In ************

    public function redirectToLinkedInProvider(Request $request){
        return Socialite::driver('linkedin')->redirect();
    }

    public function responseFromLinkedInCallback(Request $request){

        try{
            $user = Socialite::driver('linkedin')->user();
            dd($user);
        }
        catch(Exception $e)
        {
                return redirect()->back()->with('error_message', 'Error! Something went wrong try to login again.');
        }

    }

    //***************** Callback through Linked In ***********************

    public function redirectToFacebookProvider(Request $request){
        
        return Socialite::driver('facebook')->redirect();
    }

    public function responseFromFacebookCallback(Request $request){
        
        try{
            $user = Socialite::driver('facebook')->user();
            dd($user);

        }
        catch(Exception $e)
        {
                return redirect()->back()->with('error_message', 'Error! Something went wrong try to login again.');
        }
        
    }


public function downloadExcel()
   {
       #$data = Addbranch::get()->toArray();
       $data = user::get()->toArray();
       return Excel::create('laravelcode', function($excel) use ($data) {
           $excel->sheet('mySheet', function($sheet) use ($data)
           {
               $sheet->fromArray($data);
           });
       })->download();
   }


}
