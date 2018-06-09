<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon;
use Notification;
use App\Notifications\SendDailySummary as DS;
use DB;

class DailySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:daily';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily summary about users on the day';

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
        // write ur code here
        $user = DB::table('users')->count();
        
        //$user = User::where('created_at', '=', Carbon\Carbon::today())->where('updated_at', '=', Carbon\Carbon::today())->count();

        //*******************************************************************************//
            // $when = $this->argument('when');
            // if ($when == 'evening') {
            //     $heading = 'Summary of '.Carbon\Carbon::today()->toRfc850String().' to '.Carbon\Carbon::today()->addHours(18)->addMinutes(0)->toRfc850String().' whole day.';
            //     //Notification::send(User::first(), new DS($when,$heading,$postings, $odvi, $live, $todays_videos, $pending_compression, $queued_compression, $failed_compression, $corporates_registered_today, $invitaions_for_odvi_sent_today, $SMSCredits, $videos_of_today, $videos_of_tonight, $active_postings, $videos_of_tonight ));
            // }

        $heading = 'Summary of '.Carbon\Carbon::today()->toRfc850String().' to '.Carbon\Carbon::today()->addHours(18)->addMinutes(0)->toRfc850String().' whole day.';
        //$userdetails = User::first();
        ///dd($userdetails->email);
        $status = Notification::send(User::first(), new DS($heading, $user));
        
    }
}
