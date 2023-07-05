<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Vehicle;

class VehicleController extends Controller
{

    private $error_msg='';
    public function test(){
        $data=Vehicle::all();
        return $data;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVehicleTables(Request $request){
        try{
            $vehicle_status=$request->vehicleStatus;
            if($vehicle_status!=4){
                $vehicles=DB::connection('mysql')->table('vehicle as v')
                ->select('v.shipping_agent_service_code','b.branch_location as branchname','v.registration_number','v.vehicle_type',
                'v.delivery_capacity','v.target_amount','v.vehicle_id','v.van_number','v.vehicle_status',
                'v.start_date','v.end_date','v.display_order')
                ->join('branch as b','b.branch_id','v.branch_id')
                ->whereRaw('v.vehicle_status = ?',array($vehicle_status))
                ->orderBy('b.branch_location','ASC')
                ->get();
            }
            else{
                $vehicles=DB::connection('mysql')->table('vehicle as v')
                ->select('v.shipping_agent_service_code','b.branch_location as branchname','v.registration_number','v.vehicle_type',
                'v.delivery_capacity','v.target_amount','v.vehicle_id','v.van_number','v.vehicle_status',
                'v.start_date','v.end_date','v.display_order')
                ->join('branch as b','b.branch_id','v.branch_id')
                ->orderBy('b.branch_location','ASC')
            ->get();
            
            }
            return response()->json([
                'vehicles'=> $vehicles
            ]);
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
        }
    }
     /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request  
     * @return \Illuminate\Http\Response
     */
    public function getVehicleDetailsById(Request $request){
        $this->error_msg='';
        $vehicle='';
        try{
            $id=$request->vehicleId;
            $vehicle=DB::connection('mysql')->table('vehicle as v')
            ->select('v.shipping_agent_service_code','v.branch_id','b.branch_location as branchname','v.registration_number','v.vehicle_type',
            'v.delivery_capacity','v.target_amount','v.vehicle_id','v.van_number','v.vehicle_status',
            'v.start_date','v.end_date','v.display_order')
            ->join('branch as b','b.branch_id','v.branch_id')
            ->whereRaw('v.vehicle_id=?',array($id))
            ->first();
           
            return response()->json([
                 $vehicle
            ]);
            
        }   
        catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg="Could not connect to the database ".$e;
            return $this->error_msg;
        }      
    }
    /**
     * Display the specified resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateVehicleDetailsById(Request $request){
        try{
            $id=$request->vehicleId;
            $form=$request->vehicleForm;
            $getVehicleRecord=DB::connection('mysql')->table('vehicle')->whereRaw('vehicle_id=?',array($id))->first();
            $updateVehicle='';
            $DB_status='';
            $condition='';
            $updateBookingStatus='';
            $updateVehicleBookingStatus='';
            /*vehicle_status Active to Not Active*/
            if($form['vehicle_status']==0){
                /*  set vehicle_status Not active(0)
                    end_date to current week monday(if current day is not monday)
                    end_date to last week monday(if current day is monday)
                    and add to excluded table
                    Change vehicle_booking_status to Not available(1) for entire booking_date in vehicle_booking_table
                */ 
                $condition='not active if';
                //print_r($condition);
                
                $getDBStatus=$this->vehicleStatusUpdate($id);
                if($getDBStatus!='')
                    $DB_status='Done';
            }
            else{
                $condition='main else';
               
                //print_r($condition);
                $updateVehicle=DB::connection('mysql')->table('vehicle')->where('vehicle_id',$id)->update($form);
                
                //print_r($condition);
                if($updateVehicle!=''){
                    /*vehicle_status Active(1) to Inservice or Repair(2 or 3) set vehicle_booking_status 1(Not available) */
                    if($getVehicleRecord->{'vehicle_status'}==1 && $form['vehicle_status']>1){
                        $condition='Active to inservice';
                        
                        //print_r($condition);
                        $updateBookingStatus=DB::connection('mysql')->table('vehicle_booking')->where('vehicle_id',$id)->update([
                            'vehicle_booking_status' => 1
                        ]);
                       // $DB_status='Done';
                    }
                    /*vehicle_status Inservice or Repair(2 or 3) to Active(1) set vehicle_booking_status 0(Available) */
                    if($getVehicleRecord->{'vehicle_status'}>1 && $form['vehicle_status']==1){
                        $condition='inservice to active';
                        
                        //print_r($condition);
                        $updateBookingStatus=DB::connection('mysql')->table('vehicle_booking')->where('vehicle_id',$id)->update([
                            'vehicle_booking_status' => 0
                        ]);
                        $DB_status='Done';
                    }
                    if($getVehicleRecord->{'branch_id'}!=$form['branch_id']){
                        $condition='branch if';
                        
                        //print_r($condition);
                        $updateVehicleBookingStatus=DB::connection('mysql')->table('vehicle_booking')->where('vehicle_id',$id)->update([
                            'branch_id' => $form['branch_id']
                        ]);
                        $DB_status='Done';           
                    }
                    $DB_status='Done';
                }
            }
            //return $DB_status,$condition,$getVehicleRecord->{'vehicle_status'},$updateVehicle,$updateBookingStatus,$updateVehicleBookingStatus; 
            return response()->json([
                $DB_status
            ]);
        }catch (\Exception $e) {
            $error="Could not connect to the database. Please check your configuration. error:" . $e ;
        }
    }

    public function deleteVehicleDetailsById(Request $request){
        try{
            $id=$request->vehicleId;
            $deleteDBStatus=$this->vehicleStatusUpdate($id);
            return $deleteDBStatus;
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
        }
    }
    public function vehicleStatusUpdate($vehicle_id){
        /*  set vehicle_status Not active(0)
            end_date to current week monday(if current day is not monday)
            end_date to last week monday(if current day is monday)
            and add to excluded table
            Change vehicle_booking_status to Not available(1) for entire booking_date in vehicle_booking_table
        */ 
        try{
            $currentDate=date('Y-m-d');
            $currentDay=date('l');
            $newSelectedDateFormat= new Carbon($currentDate);
            $startOfThisWeek=$newSelectedDateFormat->startOfWeek()->format('Y-m-d');
            $startOfLastWeek=$newSelectedDateFormat->startOfWeek()->subDays(7)->format('Y-m-d');
            $updateVehicle='';
            $addVehicleIdtoExcluded='';
            $updateBookingStatus='';
            $vehicleUpdateStatus='';
            if($currentDay!='Monday'){
                $condition='if';
                $updateVehicle=DB::connection('mysql')->table('vehicle')->where('vehicle_id',$vehicle_id)->update([
                    'vehicle_status' => 0,
                    'end_date'=>$startOfThisWeek
                ]);
                $vehicleUpdateStatus='Done';
            }
            else{
                $condition='else';
                $updateVehicle=DB::connection('mysql')->table('vehicle')->where('vehicle_id',$vehicle_id)->update([
                    'vehicle_status' => 0,
                    'end_date'=>$startOfLastWeek
                ]);
                $vehicleUpdateStatus='Done';
            }
            if($vehicleUpdateStatus!=''){
                $vehicleUpdateStatus='';
                $addVehicleIdtoExcluded=DB::connection('mysql')->table('vehicles_excluded')->insert([
                    'vehicle_id'=>$vehicle_id
                ]);
                if($addVehicleIdtoExcluded!=''){
                    $updateBookingStatus=DB::connection('mysql')->table('vehicle_booking')->where('vehicle_id',$vehicle_id)->update([
                        'vehicle_booking_status' => 1
                    ]);
                    $vehicleUpdateStatus='Done';
                }
            }
            return $vehicleUpdateStatus;
        }catch (\Exception $e) {
            $error="Could not connect to the database. Please check your configuration. error:" . $e ;
        }
        
    }
    public function checkVanNo(Request $request){
        $result='';
        $error='';
        try{
            $checkVanNo=DB::connection('mysql')->table('vehicle')
            ->whereRaw('shipping_agent_service_code = ?',array($request->vanNo))->first();
            if(!isset($checkVanNo->shipping_agent_service_code)){
                $result='new';
            }else{
                $result='exist';
            }
        }catch (\Exception $e) {
            $error="Could not connect to the database. Please check your configuration. error:" . $e ;
        }
        return response()->json([
            'error'=>$error,
            'result'=>$result
        ]);
    }
    public function createVehicleDetails(Request $request){
        try{
            $form=$request->vehicleForm;
           // $vehiclewithoutrun = str_split($form['shipping_agent_service_code'], 6);
            
            // $addVehicleWithoutRun=DB::connection('mysql')->table('vehicle')
            // ->upsert([
            //     //'vehicle_id' => NULL,
            //     'van_number'=>$form['van_number'],
            //     'start_date'=>$form['start_date'],
            //     'end_date'=>$form['end_date'] ,
            //     'branch_id'=>$form['branch_id'] ,
            //     'shipping_agent_service_code'=>$vehiclewithoutrun[0] ,
            //     'delivery_capacity'=>$form['delivery_capacity'] ,
            //     'target_amount'=> $form['target_amount'] ,
            //     'registration_number'=>$form['registration_number'],
            //     'vehicle_type' => $form['vehicle_type'],
            //     'vehicle_status'=>$form['vehicle_status'],
            //     'quartix_code'=>'' ,
            //     'vehicle_suppl_codes'=>'',
            //     'max_divergence'=>25,
            //     'display_order'=>100
            // ],'shipping_agent_service_code');

            $addVehicle=DB::connection('mysql')->table('vehicle')
            ->upsert([
                //'vehicle_id' => NULL,
                'van_number'=>$form['van_number'],
                'start_date'=>$form['start_date'],
                'end_date'=>$form['end_date'] ,
                'branch_id'=>$form['branch_id'] ,
                'shipping_agent_service_code'=>$form['shipping_agent_service_code'] ,
                'delivery_capacity'=>$form['delivery_capacity'] ,
                'target_amount'=> $form['target_amount'] ,
                'registration_number'=>$form['registration_number'],
                'vehicle_type' => $form['vehicle_type'],
                'vehicle_status'=>$form['vehicle_status'],
                'quartix_code'=>'' ,
                'vehicle_suppl_codes'=>'',
                'max_divergence'=>25,
                'display_order'=>100
            ],'shipping_agent_service_code');
           
            $getVehicleID=DB::connection('mysql')->getPdo()->lastInsertId();
            $currentDate=date('Y-m-d');
            $weekStartDate=Carbon::now()->startOfWeek()->format('Y-m-d');
            $newSelectedDateFormat= new Carbon($currentDate);
            //$currentYearDate=$newSelectedDateFormat->format('Y-m-d');
            $next3yearsDate=$newSelectedDateFormat->addYear(3)->format('Y-m-d');
            $interval_period = CarbonPeriod::create($weekStartDate, $next3yearsDate);

            if($getVehicleID!='' && $addVehicle!=''){
                foreach($interval_period as $date){
                    $booking_date=$date->format('Y-m-d');
                    $dayofWeek=$date->format('w');
                    //print_r($dayofWeek);
                    if($dayofWeek == 0 || $dayofWeek == 6)
                        $booking_status=1;
                    else
                        $booking_status=0;
                    $updateBookingStatus=DB::connection('mysql')->table('vehicle_booking')
                    ->insert([
                        'vehicle_booking_status' => $booking_status,
                        'vehicle_booking_date'=>$booking_date,
                        'branch_id'=>$form['branch_id'],
                        'vehicle_id'=>$getVehicleID
                    ]);	
                }
            }
            return response()->json([
                $getVehicleID
            ]);
           

        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
        }
    }
}
