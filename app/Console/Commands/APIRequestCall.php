<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class APIRequestCall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to send api call';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "\n=====================================\n";
        echo '\nSchedule job every min in handle\n';
        $vehicleDiaryController = app(\App\Http\Controllers\DespatchDiaryController::class);
        
        $selected_branch_id=0;
        $weekStartDate = Carbon::now()->format('Y-m-d');
        $weekEndDate=Carbon::now()->addMonths(2)->format('Y-m-d');
        $result= $vehicleDiaryController->callAPIRequestForOrders($selected_branch_id,$weekStartDate,$weekEndDate);
        // echo "REsult:";
        // print_r($result);
        // $getDataFromNav=DB::connection('sqlsrv')->select('exec dbo.DeliveryDiaryOrders ?,?,?',array('2022-10-10','2022-10-16','BEDSBRANCH'));
        // print_r($getDataFromNav);
        //return 0;
    }
}
