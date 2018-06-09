<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Requests;
use App\rewards;
use App\user_credits;
use Session;
use DB;
use Log;
use Exception as Exception1;
use Config;
use Carbon;

class DeleteCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deleteCredits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Expired Credits Of users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            
            $today = Carbon\Carbon::today()->toDateString();
            $rewards=rewards::select('*')->where('credit_expiry_date','<',$today)->where('is_expired',0)->get()->toArray();
            
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
            
        }
        catch(Exception1 $e)
        {
            echo $e->getMessage();
            exit;
        }   
    }
}
