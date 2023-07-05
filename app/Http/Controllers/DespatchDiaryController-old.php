<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Postcode;
use App\Models\Vehicle;
use App\Models\DiaryRecords;
use App\Models\DiaryComments;
use App\Models\VehicleBookingStatus;
use App\Models\APIRouting;
use App\Models\Branch;
use \Exception;

class DespatchDiaryController extends Controller
{
    private $error_msg='';
    // protected $mapQuest_API_KEY = "Fmjtd%7Cluu821u72g%2C7x%3Do5-942adu";
    //protected $mapQuest_API_KEY = "wkWpyAa3gy4zGWzu4g1nZM3GMgTA5myJ";
    protected $mapQuest_API_KEY="L2UUtwxWypijnmErfufZ4fjqxLu58Idh";
    /**
     * Get api routing order_nos for van per day
     */
    public function getAPIOrderNumbers($uniqueId)
    {
        try {
            $order_numbers = APIRouting::where('unique_id', $uniqueId)->value('order_nos');

        } catch (\Exception $e) {
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
        return json_decode($order_numbers, true);
    }
    /**
     * Get api routing details for van per day
     */
    public function getAPIRoutingDetails($uniqueId)
    {
        $routing_details = '';
        try {
            //$routing_details = DB::connection('mysql')->table('api_routing')->select('time_seconds', 'distance_miles', 'original_route', 'display_orders_position')->where('unique_id', $uniqueId)->get();
            $routing_details = APIRouting::select('time_seconds', 'distance_miles', 'original_route', 'display_orders_position')
            ->where('unique_id', $uniqueId)->get();

        } catch (\Exception $e) {
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
        return $routing_details;
    }
    /**
     * update routing records in table
     *
     */
    public function setAPIRoutingDetails($api_routing)
    {
        try {
            
            DB::connection('mysql')->table('api_routing')
                        ->upsert($api_routing, 'unique_id');
            // $updateOrInsert = DB::connection('mysql')->table('api_routing')
            //     ->updateOrInsert(['unique_id' => $api_routing['unique_id']],
            //         $api_routing);
        } catch (\Exception $e) {
            //dump("Api routing fail");
            //dd($api_routing);
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**
     * optimize route api for orders
     * @throws Exception
     */
    public function getMapQuestDistance($branchLatLong, $toAddress)
    {
        $this->error_msg='';
        try {
            $mapQuest_API_KEY = $this->mapQuest_API_KEY;
            // Cache::flush();
            //updated by soumya
                if(!isset($postcode_details_api['route'])){
                    Cache::forget('mapquest.' . $branchLatLong . $toAddress);
                    //print_r('Error');
                    $this->error_msg = 'Error';
                    return 'Error';
               }
           
        //     //Cache::forget('mapquest.' . $branchLatLong . $toAddress);
            
        //     //updated by soumya
        //     $postcode_details_api = Cache::remember('mapquest.' . $branchLatLong . $toAddress, 360, function () use ($toAddress, $mapQuest_API_KEY, $branchLatLong) {

        //         $toAddresses = explode('&to=', $toAddress);
        //         array_shift($toAddresses);

        //         $toChunks = array_chunk($toAddresses, 25);
        //         $totalJourneyTime = 0;
        //         $totalJourneyDistance = 0;
        //         $optimizedRouteSequence = [];

                foreach ($toChunks as $toChunk) {
                    $response = Http::get('http://www.mapquestapi.com/directions/v2/optimizedroute', [
                        'key' => rawurldecode($mapQuest_API_KEY),
                        'doReverseGeocode' => false,
                        'from' => $branchLatLong,
                        'to' => $toChunk
                    ])->throw();

                    $jsonResponseDelivery = $response->json();
                    //print_r($jsonResponseDelivery);
                    if (empty($jsonResponseDelivery) || !isset($jsonResponseDelivery['route']['time'])) {
                        print_r("Error");
                        $this->error_msg = 'Error';
                        $postcode_details_api = "Error";
                        break;
                    } else {
                        $journeyTime = $jsonResponseDelivery['route']['time'];
                        $journeyDistance = $jsonResponseDelivery['route']['distance'];
                        $optimizedRouteSequenceChunk = $jsonResponseDelivery['route']['locationSequence'];

        //                 $totalJourneyTime += $journeyTime;
        //                 $totalJourneyDistance += $journeyDistance;
        //                 $optimizedRouteSequence = array_merge($optimizedRouteSequence, $optimizedRouteSequenceChunk);
        //             }
                    
        //         }

                if ($this->error_msg!='Error') {
                    $postcode_details_api = [
                        'journeyTime' => $totalJourneyTime,
                        'journeyDistance' => $totalJourneyDistance,
                        'optimizeRouteSequence' => $optimizedRouteSequence
                    ];
                } else {
                    Log::error($response);
                    print_r($response);
                    throw new \Exception('Unable to calculate journey time, please check logs for the API response');
                }
               
        //         return $postcode_details_api;
        //     });
        // } catch (\Illuminate\Http\Client\ConnectionException $e) {
        //     $this->error_msg = 'error';
        //     echo "map quest Exception" . $e->getMessage();
        //     return "Exception" . $e->getMessage();
        // }
        /**SOUMYA */
        // try{
        //     $postcode_details_api='';
            
        //     $mapQuestDirecitonsUrl = 'http://www.mapquestapi.com/directions/v2/optimizedroute?key=';
        //     $apiaddress = $mapQuestDirecitonsUrl . $this->mapQuest_API_KEY . '&doReverseGeocode=false&from='.$branchLatLong . $toAddress;
        //    //  echo "Final map quest request :\n";
        //     // print_r($apiaddress);
        //     $totalJourneyTime = 0;
        //     $totalJourneyDistance = 0;
        //     $optimizedRouteSequence = [];
        //     $ch = curl_init($apiaddress);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        //     curl_setopt($ch, CURLOPT_TIMEOUT, 1400); //timeout in seconds
        //     //curl_setorespt($ch, CURLOPT_TIMEOUT, 1400);

        //     $response = curl_exec($ch);
        //     if (curl_errno($ch)) {
        //         //echo "Error:\n";
        //         $this->error_msg = curl_error($ch);
        //         $postcode_details_api = "Error";
        //         print_r($this->error_msg);
        //     }
        //     curl_close($ch);
        //     //   echo "response:\n";
        //     //  print_r($response);
        //     $jsonResponseDelivery = json_decode($response, TRUE);
        //     // echo "\njson value:";
        //     // print_r($jsonResponseDelivery);
        //     if(empty($jsonResponseDelivery) || !isset($jsonResponseDelivery['route']['time'])){
        //         //print_r("enter into if");
        //         $this->error_msg = 'Error';
        //         $postcode_details_api = "Error";
        //         //break;
        //     }
        //     else{
        //         $totalJourneyTime = $jsonResponseDelivery['route']['time'];
        //        // print_r($totalJourneyTime);
        //         $totalJourneyDistance = $jsonResponseDelivery['route']['distance'];
        //         $optimizedRouteSequence = $jsonResponseDelivery['route']['locationSequence'];
        //         //$postcode_details_api=['journeyTime' =>$jsonResponseDelivery['route']['time']  ,'journeyDistance' =>  $jsonResponseDelivery['route']['distance'], 'optimizeRouteSequence' =>  $jsonResponseDelivery['route']['locationSequence']];
        //     }
        //     // echo "error msg2:::";
        //     // print_r($this->error_msg);
        //     // print_r(!isset($this->error_msg));
        //     // echo "emd::";
        //     if ($this->error_msg!='Error') {
        //         $postcode_details_api = [
        //             'journeyTime' => $totalJourneyTime,
        //             'journeyDistance' => $totalJourneyDistance,
        //             'optimizeRouteSequence' => $optimizedRouteSequence
        //         ];
        //     } else {
        //         //Log::error($response);
        //         print_r($response);
        //         $postcode_details_api = "Error";
        //         throw new \Exception('Unable to calculate journey time, please check logs for the API response');
        //     }
        //    // print_r($this->error_msg);
        //    // print_r($postcode_details_api);
        //     return $postcode_details_api;

        // }catch (\Illuminate\Http\Client\ConnectionException $e) {
        //     $this->error_msg='Error';
        //     echo "Exception" .$e->getMessage();
        //     return "Exception" .$e->getMessage();
        // }
        /** To call multiple API keys-Soumya */
        try {
            $postcode_details_api = '';
            $mapQuestDirecitonsUrl = 'http://www.mapquestapi.com/directions/v2/optimizedroute?key=';
            $apiKeys = [$this->mapQuest_API_KEY1, $this->mapQuest_API_KEY2];
            $apiaddress = '';
            $response = null;
            $jsonResponseDelivery = null;
        
            foreach ($apiKeys as $apiKey) {
                $apiaddress = $mapQuestDirecitonsUrl . $apiKey . '&doReverseGeocode=false&from=' . $branchLatLong . $toAddress;
        
                $ch = curl_init($apiaddress);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1400);
        
                $response = curl_exec($ch);
                if (!curl_errno($ch)) {
                    $jsonResponseDelivery = json_decode($response, true);
                    if (!empty($jsonResponseDelivery) && isset($jsonResponseDelivery['route']['time'])) {
                        break;
                    }
                }
                //print_r($apiaddress);
                curl_close($ch);
            }
        
            if (empty($jsonResponseDelivery) || !isset($jsonResponseDelivery['route']['time'])) {
                $postcode_details_api = 'Error';
               // throw new \Exception('Unable to calculate journey time, please check logs for the API response');
            } else {
                $totalJourneyTime = $jsonResponseDelivery['route']['time'];
                $totalJourneyDistance = $jsonResponseDelivery['route']['distance'];
                $optimizedRouteSequence = $jsonResponseDelivery['route']['locationSequence'];
        
                $postcode_details_api = [
                    'journeyTime' => $totalJourneyTime,
                    'journeyDistance' => $totalJourneyDistance,
                    'optimizeRouteSequence' => $optimizedRouteSequence
                ];
            }
        
            return $postcode_details_api;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error_msg = 'Error';
            echo "Exception: " . $e->getMessage();
            return "Exception: " . $e->getMessage();
        } catch (\Exception $e) {
            $this->error_msg = 'Error';
            echo "Exception: " . $e->getMessage();
            return "Exception: " . $e->getMessage();
        }
        
    }

   
   

    public function getGmapsDistance($originalSequence){
     
         $route='';
         $gMapResponse = null;
        // first index of orignal sequence array has the source of route, and the last index has the destination

         $selected_branch_postcode = $originalSequence[0];
            
        try {
            
            foreach ($originalSequence as $oneTableRoute)
            {
                if($oneTableRoute==$selected_branch_postcode)
                {
                    continue;
                }
                else{
                    $route .= $oneTableRoute.'|';
                }

            }

            $route  = rtrim($route, "|");
            
            $route =  str_replace(' ', '', $route);
            

            $GmapsDirectionsUrl = 'https://maps.googleapis.com/maps/api/directions/json?destination='.$selected_branch_postcode
            .'&origin='.$selected_branch_postcode
            .'&waypoints=optimize%3Atrue%7C';


           $GmapsDirectionsUrl = str_replace(' ', '', $GmapsDirectionsUrl);
           $apiKey = "&key=AIzaSyCXntRD8HoRjEc_1lto4Zd8MQnuGCAQqQg";
           $apiKey = str_replace(' ', '', $apiKey);
           $apiaddress = $GmapsDirectionsUrl.$route. $apiKey;
           $apiaddress  = str_replace(' ', '', $apiaddress);
           
            

           $ch = curl_init($apiaddress);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
           curl_setopt($ch, CURLOPT_TIMEOUT, 1400);
    
           $gMapResponse = curl_exec($ch);
         
            curl_close($ch);   
    
        } 
       catch (\Illuminate\Http\Client\ConnectionException $e) {
           $this->error_msg = 'Error';
           echo "Exception: " . $e->getMessage();
           return "Exception: " . $e->getMessage();
        } catch (\Exception $e) {
           $this->error_msg = 'Error';
           echo "Exception: " . $e->getMessage();
           return "Exception: " . $e->getMessage();
       }

        $gMapResponse= json_decode($gMapResponse, true);
        return $gMapResponse;
    
    }
    /**
     * Function is geting lat and long from postcode
     * if we don't find postcode in database go to third part API to update our database
     *
     * @postcode -> MK40 1EQ
     *
     * return -> lat and long
     */
    public function sendPostcodeToApi($postcode)
    {
        try {
            $latLongDB = DB::connection('mysql')->table('postcodes')->select('latitude', 'longitude')->where('postcode', $postcode)->get();
            if (count($latLongDB) == 0 || $latLongDB->count() == 0) {
                $apiResponse = json_decode(file_get_contents("https://api.postcodes.io/postcodes/$postcode"));
                if ($apiResponse->status == 200 && ($apiResponse->result->latitude != null
                        && $apiResponse->result->longitude != null)) {

                    $latLong = $apiResponse->result->latitude . ',' . $apiResponse->result->longitude;
                    $data = array(
                        'postcode' => $postcode,
                        'latitude' => $apiResponse->result->latitude,
                        'longitude' => $apiResponse->result->longitude
                    );
                    $this->updateLatLongInPostcode($data);
                } else {
                    $latLong = '';
                }
            } else {
                $latLong = number_format($latLongDB[0]->latitude, 8) . ',' . number_format($latLongDB[0]->longitude, 8);
            }

            return $latLong;
        } catch (\Throwable $th) {
            $latLong = '';
            return $latLong;
        }
    }
     /**
     * update latitude and longitude in postcodes table
     */
    public function updateLatLongInPostcode($data)
    {

        try {
            $updateOrInsert = DB::connection('mysql')->table('postcodes')
                ->updateOrInsert(['postcode' => $data['postcode']], $data);
        } catch (\Exception $e) {
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }

    }
    /**
     * Fetch BranchCode based on branchId
     * @param integer $branchID
     * @return single column string result
     */
    public function getBranchShippingCode($branch_id)
    {
        try {
            // $getBranchCode=DB::connection('mysql')->table('branch')->where('branch_id',$selected_branch_id)->value('branch_code');
            return Branch::where('branch_id', $branch_id)->value('shipping_agent_code');
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
    }
    
    /**
     * Fetch all BranchCodes based on branchId
     * @param integer $branchID
     * @return single column string result
     */
    public function getAllBranchShippingCodes()
    {
        try {
            // $getBranchCode=DB::connection('mysql')->table('branch')->where('branch_id',$selected_branch_id)->value('branch_code');
            return Branch::pluck('shipping_agent_code')->toArray();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
    }
    /**
     * Fetch BranchCode based on branchId
     * @param integer $branchID
     * @return single column string result
     */
    public function getBranchCode($branch_id)
    {
        try {
            // $getBranchCode=DB::connection('mysql')->table('branch')->where('branch_id',$selected_branch_id)->value('branch_code');
            return Branch::where('branch_id', $branch_id)->value('branch_code');
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
    }
     /**
     * Get count of branches
     */
    public function getBranchIdsList()
    {
        try {
            return Branch::pluck('branch_id');
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
    }
     /**
     * Get postcode for selected branch
     */
    public function getSelectedBranchPostCode($branch_id)
    {
        try {
            // $getBranchCode=DB::connection('mysql')->table('branch')->where('branch_id',$selected_branch_id)->value('branch_code');
            return DB::connection('mysql')->table('branch')->where('branch_id', $branch_id)->value('branch_postcode');
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
    }
    /**
     * Get count of branches
     */
    public function getDistinctCountOfBranches()
    {
        try {
            return Branch::distinct()->count('shipping_agent_code');
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
    }
    /**
     * Get branch list
     */
    public function getAllBranchDetails()
    {
        try {
            // $getBranchCode=DB::connection('mysql')->table('branch')->where('branch_id',$selected_branch_id)->value('branch_code');
            return Branch::select('branch_id', 'branch_location')->get();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
    }
    /**
     * Get lat and longitude for selected branch
     */
    public function getBranchLatAndLong($branch_id)
    {
        try {
            // $getBranchCode=DB::connection('mysql')->table('branch')->where('branch_id',$selected_branch_id)->value('branch_code');
            $branchLatAndLong = Branch::select('latitude', 'longitude')->where('branch_id', $branch_id)->get();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
        return $branchLatAndLong;
    }
    /**
     * get comments list
     */
    public function getCommentsIndependently($dateFrom, $dateTo)
    {
        try {
            $commentsIndependently = DiaryComments::from('vehicle_diary_comments as vdc')
                ->whereBetween('vdc.vehicle_diary_date',[$dateFrom, $dateTo])
                ->join('vehicle as v', 'vdc.vehicle_id', 'v.vehicle_id')
                ->select('v.shipping_agent_service_code', 'vdc.vehicle_diary_date', 'vdc.comments')
                ->get();
            return $commentsIndependently;
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }

    }
    /**
     * Inputs -comment content,vehicle id & comment date from comments textarea
     *
     */
    public function sendCommentsToDB(Request $request)
    {

        try {
            //$comment_date=$request->selectedDate;
            $selectedDate = date('Y-m-d', strtotime($request->selectedDate));
            $newSelectedDateFormat = new Carbon($selectedDate);
            $comment_date = $newSelectedDateFormat->format('Y-m-d');
            // console.log($comment_date+":"+$comment_content+":" + $getVehicleId );
            $comment_content = $request->updatedComments;
            $getVehicleId = $request->selectedVehicleNo;
            $getVehicleId = $this->getVehicleIdForCode($request->selectedVehicleNo);

            if(DB::connection('mysql')->table('vehicle_diary_comments')
            ->where([
            'vehicle_diary_date' => $comment_date, 
            'vehicle_id' => $getVehicleId])->count()>1){
                $deleteDBStatus=DB::connection('mysql')->table('vehicle_diary_comments')
                ->where([
                'vehicle_diary_date' => $comment_date, 
                'vehicle_id' => $getVehicleId])->delete();

                $updateCommentResponse = DB::connection('mysql')->table('vehicle_diary_comments')
                ->updateOrInsert(['vehicle_diary_date' => $comment_date, 'vehicle_id' => $getVehicleId],
                    ['comments' => $comment_content, 'vehicle_diary_date' => $comment_date, 'vehicle_id' => $getVehicleId]);
            }
            else{
                $updateCommentResponse = DB::connection('mysql')->table('vehicle_diary_comments')
                ->updateOrInsert(['vehicle_diary_date' => $comment_date, 'vehicle_id' => $getVehicleId],
                    ['comments' => $comment_content, 'vehicle_diary_date' => $comment_date, 'vehicle_id' => $getVehicleId]);
            }
            return $updateCommentResponse;
            
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**Fetch van details
     * @param start_date
     * branch_id
     */
    public function getVanDetails($selected_branch_id,$date){
        try {
            $getVanDetails='';
            
            if($selected_branch_id!=0){
                $getVanDetails=Vehicle::select('shipping_agent_service_code', 
                                'collapse_status', 'parent_collapse', 'vehicle_bookable', 
                                'branch_id', 'registration_number', 'vehicle_type','delivery_capacity', 
                                'target_amount', 'vehicle_id','van_number',
                                'start_date', 'end_date', 'display_order')
                    ->where([
                        ['vehicle_status','=',1],
                        ['start_date','<=',$date],
                        ['end_date','>=',$date],
                        ['branch_id','=',$selected_branch_id]
                    ])
                    ->orderBy('display_order','ASC')
                    ->get();
                
            }else{
                $getVanDetails=Vehicle::select('shipping_agent_service_code', 
                                'collapse_status', 'parent_collapse', 'vehicle_bookable', 
                                'branch_id', 'registration_number', 'vehicle_type','delivery_capacity', 
                                'target_amount', 'vehicle_id','van_number',
                                'start_date', 'end_date', 'display_order')
                    ->where([
                        ['vehicle_status','=',1],
                        ['start_date','<=',$date],
                        ['end_date','>=',$date]
                    ])
                    ->orderBy('display_order','ASC')
                    ->get();
            }
            
            return $getVanDetails;
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }

    public function getDistinctVanDetails($selected_branch_id,$date){
        
        try {
<<<<<<< HEAD:app/Http/Controllers/DespatchDiaryController.php
            $getVanDetails='';
            
            if($selected_branch_id!=0){
                $getVanDetails=Vehicle::select(
                'branch_id', 'registration_number','parent_collapse' ,'vehicle_type','delivery_capacity', 
                'target_amount','van_number')
                    ->where([
                        ['vehicle_status','=',1],
                        ['start_date','<=',$date],
                        ['end_date','>=',$date],
                        ['branch_id','=',$selected_branch_id]
                    ])
                    ->distinct('van_number')
                    ->orderBy('display_order','ASC')
                    ->get();
                
            }else{
                $getVanDetails=Vehicle::select('branch_id', 'parent_collapse','registration_number', 'vehicle_type','delivery_capacity', 
                'target_amount','van_number')
                    ->where([
                        ['vehicle_status','=',1],
                        ['start_date','<=',$date],
                        ['end_date','>=',$date]
                    ])
                    ->distinct('van_number')
                    ->orderBy('display_order','ASC')
                    ->get();
=======

            return DB::connection('mysql')->table('diary as d')
                ->select('d.shipping_agent_service_code as van_number',
                    DB::raw('SUM(d.order_weight) as totalWeight'),
                    DB::raw('SUM(d.order_amount) as totalValue'))
                ->whereRaw("d.shipping_agent_service_code LIKE ? AND d.promised_delivery_date BETWEEN ? AND ?", array($vanId, $dateFrom, $dateTo))
                ->groupBy('d.shipping_agent_service_code')->get();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    public function getCourierData($selectedBranch, $fromDate, $toDate)
    {
        $this->error_msg = '';
        $courier = array();
        try {
            if ($selectedBranch == 'All') {
                $courier = DB::connection('mysql')->table('diary as d')
                    ->select('d.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 'd.order_weight',
                        'd.order_amount', 'd.currency_code', 'd.location_code', 'd.shipping_agent_code', 'd.shipment_type', 'd.promised_delivery_date',
                        'd.delivery_confirmed', 'd.last_update', 'd.balance_amount', 'd.last_shipping_no', 'd.ship_status', 'd.completed', 'd.dispatch_requested_date')
                    ->whereRaw("d.shipping_agent_service_code =? AND d.promised_delivery_date BETWEEN ? AND ?", array('COURIER', $fromDate, $toDate))
                    ->orderBy('d.promised_delivery_date', 'ASC')->get();
            } else {
                $courier = DB::connection('mysql')->table('diary as d')
                    ->select('d.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 'd.order_weight',
                        'd.order_amount', 'd.currency_code', 'd.location_code', 'd.shipping_agent_code', 'd.shipment_type', 'd.promised_delivery_date',
                        'd.delivery_confirmed', 'd.last_update', 'd.balance_amount', 'd.last_shipping_no', 'd.ship_status', 'd.completed', 'd.dispatch_requested_date')
                    ->whereRaw("d.location_code = ? AND d.shipping_agent_service_code =? AND d.promised_delivery_date BETWEEN ? AND ?", array($selectedBranch, 'COURIER', $fromDate, $toDate))
                    ->orderBy('d.promised_delivery_date', 'ASC')->get();
>>>>>>> c96b118f9143e2c01992818c809d85c0737fe016:app/Http/Controllers/TestNavANDDiaryController.php
            }
            
            return $getVanDetails;
        } catch (\Exception $e) {
<<<<<<< HEAD:app/Http/Controllers/DespatchDiaryController.php
=======
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    public function getPostData($selectedBranch, $fromDate, $toDate)
    {
        $this->error_msg = '';
        $post = array();
        try {
            if ($selectedBranch == 'All') {
                $post = DB::connection('mysql')->table('diary as d')
                    ->select('d.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 'd.order_weight',
                        'd.order_amount', 'd.currency_code', 'd.location_code', 'd.shipping_agent_code', 'd.shipment_type', 'd.promised_delivery_date',
                        'd.delivery_confirmed', 'd.last_update', 'd.balance_amount', 'd.last_shipping_no', 'd.ship_status', 'd.completed', 'd.dispatch_requested_date')
                    ->whereRaw("d.shipping_agent_service_code =? AND d.promised_delivery_date BETWEEN ? AND ?", array('POST', $fromDate, $toDate))
                    ->orderBy('d.promised_delivery_date', 'ASC')->get();
            } else {
                $post = DB::connection('mysql')->table('diary as d')
                    ->select('d.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 'd.order_weight',
                        'd.order_amount', 'd.currency_code', 'd.location_code', 'd.shipping_agent_code', 'd.shipment_type', 'd.promised_delivery_date',
                        'd.delivery_confirmed', 'd.last_update', 'd.balance_amount', 'd.last_shipping_no', 'd.ship_status', 'd.completed', 'd.dispatch_requested_date')
                    ->whereRaw("d.location_code = ? AND d.shipping_agent_service_code =? AND d.promised_delivery_date BETWEEN ? AND ?", array($selectedBranch, 'POST', $fromDate, $toDate))
                    ->orderBy('d.promised_delivery_date', 'ASC')->get();
            }
            return $post;
        } catch (\Exception $e) {
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    public function getCollectionData($selectedBranch, $fromDate, $toDate)
    {
        $this->error_msg = '';
        try {
            if ($selectedBranch == 'All') {
                $collection = DB::connection('mysql')->table('diary as d')
                    ->select('d.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 'd.order_weight',
                        'd.order_amount', 'd.currency_code', 'd.location_code', 'd.shipping_agent_code', 'd.shipment_type', 'd.promised_delivery_date',
                        'd.delivery_confirmed', 'd.last_update', 'd.balance_amount', 'd.last_shipping_no', 'd.ship_status', 'd.completed', 'd.dispatch_requested_date')
                    ->whereRaw("d.shipping_agent_service_code =? AND d.promised_delivery_date BETWEEN ? AND ?", array('COLLECTION', $fromDate, $toDate))
                    ->orderBy('d.promised_delivery_date', 'ASC')->get();
            } else {
                $collection = DB::connection('mysql')->table('diary as d')
                    ->select('d.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 'd.order_weight',
                        'd.order_amount', 'd.currency_code', 'd.location_code', 'd.shipping_agent_code', 'd.shipment_type', 'd.promised_delivery_date',
                        'd.delivery_confirmed', 'd.last_update', 'd.balance_amount', 'd.last_shipping_no', 'd.ship_status', 'd.completed', 'd.dispatch_requested_date')
                    ->whereRaw("d.location_code = ? AND d.shipping_agent_service_code =? AND d.promised_delivery_date BETWEEN ? AND ?", array($selectedBranch, 'COLLECTION', $fromDate, $toDate))
                    ->orderBy('d.promised_delivery_date', 'ASC')->get();
            }
            return $collection;
        } catch (\Exception $e) {
            $this->error_msg = "error";
>>>>>>> c96b118f9143e2c01992818c809d85c0737fe016:app/Http/Controllers/TestNavANDDiaryController.php
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**
     * Fetch bookstatus based on dates for selected branch
     * @param integer $selectedBranch
     * @param date $fromDate ,$toDate
     * @return single column string result
     */
    public function getBookableStatus($selected_branch_id, $fromDate, $toDate)
    {
        $this->error_msg = '';
        $getBookableValues='';
        try {
            if ($selected_branch_id == 0) {
                $getBookableValues = VehicleBookingStatus::from('vehicle_booking as vb')
                    ->select('v.shipping_agent_service_code', 'vb.branch_id', 'vb.vehicle_booking_date', 
                            'vb.vehicle_booking_status')
                    ->join('vehicle as v', 'v.vehicle_id', 'vb.vehicle_id')
                    ->whereBetween('vb.vehicle_booking_date',[$fromDate, $toDate])->get();
            } else {
                $getBookableValues = VehicleBookingStatus::from('vehicle_booking as vb')
                ->select('v.shipping_agent_service_code', 'vb.branch_id', 'vb.vehicle_booking_date', 
                        'vb.vehicle_booking_status')
                ->join('vehicle as v', 'v.vehicle_id', 'vb.vehicle_id')
                ->whereBetween('vb.vehicle_booking_date',[$fromDate, $toDate])
                ->where('vb.branch_id','=',$selected_branch_id)
                ->get();
            }
            return $getBookableValues;
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg = 'Could not connect to the database' . $e;
        }
    }
    public function sendBookableValueToDB(Request $request){
        $this->error_msg='';
        $updateBook='';
        try{
            $vehicle_id=$this->getVehicleIdByNo($request->vehicleNo);
            $selectedDate = date('Y-m-d',strtotime($request->selectedDate));
            $newSelectedDateFormat= new Carbon($selectedDate);
            $updateDate = $newSelectedDateFormat->format('Y-m-d');
            //print_r($request->bookableValue);
            $updateBook=DB::connection('mysql')->table('vehicle_booking')
                    ->whereRaw('vehicle_id=? AND vehicle_booking_date=?',array($vehicle_id,$updateDate))
                    ->update(['vehicle_booking_status'=>$request->bookableValue]);
            
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg='Could not connect to the database' . $e ;
        }
        return response()->json([
            "bookableValue"=>$request->bookableValue,
            "updateStatus"=>$updateBook,
            "error_msg"=>$this->error_msg
        ]);
        
    }
    /**
     * update hide or show van tab collpase
     */

    
     public function updateParentCollapseForVehicle(Request $request){
        
        $this->error_msg='';

        try{
            $updateParentCollapseQuery=DB::connection('mysql')->table('vehicle')
            
            ->whereRaw('shipping_agent_service_code LIKE ?',array($request->vanNum . '%'))
            ->update(['parent_collapse'=>$request->parentHideOrShowStatus]);
            
            $getParentCollapseQuery=DB::connection('mysql')->table('vehicle')
            ->select('shipping_agent_service_code','parent_collapse')
            ->whereRaw('shipping_agent_service_code LIKE ?',array($request->vanNum . '%'))
            ->get();
            
            //->updateOrInsert(['shipping_agent_service_code'=>$request->vanNum],['parent_collapse'=>$request->parentHideOrShowStatus]);
            
        }
        catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg='Could not connect to the database' . $e ;
        }
        return response()->json([
            "parentHideOrShowStatus"=>$request->parentHideOrShowStatus,
            "getParentCollapseQuery"=>$getParentCollapseQuery,
            // "collapse_status"=>$collapse_status,
             "updateStatus"=>$updateParentCollapseQuery,
             "error_msg"=>$this->error_msg
        ]);
    }

    public function updateVehicleTabStatus(Request $request){
        $this->error_msg='';
       
        try{
            $updateQuery=DB::connection('mysql')->table('vehicle')
            ->updateOrInsert(['shipping_agent_service_code'=>$request->vanNo],['collapse_status'=>$request->hideOrShowStatus]);
            
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg='Could not connect to the database' . $e ;
        }
        return response()->json([
            "hideOrShowStatus"=>$request->hideOrShowStatus,
           // "collapse_status"=>$collapse_status,
            "updateStatus"=>$updateQuery,
            "error_msg"=>$this->error_msg
        ]);
        
    }
    /**get collection orders */
    public function getCollectionData($selectedBranch, $fromDate, $toDate)
    {
        $this->error_msg = '';
        try {
            if ($selectedBranch == 'All') {
                $collection = DiaryRecords::select('diary_id', 'order_no', 'ship_to_name', 'ship_to_post_code','ship_to_city',
                        'type_of_supply_code', 'order_weight','order_amount','location_code', 'shipping_agent_code', 'shipment_type', 'promised_delivery_date',
                        'delivery_confirmed', 'updated_at', 'balance_amount','ship_status','dispatch_requested_date')
                    ->where('shipping_agent_service_code','=','COLLECTION')
                    ->whereBetween('promised_delivery_date',[$fromDate,$toDate])
                    ->orderBy('promised_delivery_date', 'ASC')
                    ->get();
            } else {
                $collection = DiaryRecords::select('diary_id', 'order_no', 'ship_to_name', 'ship_to_post_code','ship_to_city',
                    'type_of_supply_code', 'order_weight','order_amount','location_code', 'shipping_agent_code', 'shipment_type', 'promised_delivery_date',
                    'delivery_confirmed', 'updated_at', 'balance_amount','ship_status','dispatch_requested_date')
                    ->where([
                        ['shipping_agent_service_code','=','COLLECTION'],
                        ['location_code','=',$selectedBranch]
                    ])
                    ->whereBetween('promised_delivery_date',[$fromDate,$toDate])
                    ->orderBy('promised_delivery_date', 'ASC')
                    ->get();
            }
            return $collection;
        } catch (\Exception $e) {
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**get post order */
    public function getPostData($selectedBranch, $fromDate, $toDate)
    {
        $this->error_msg = '';
        $post = array();
        try {
            if ($selectedBranch == 'All') {
                $post = DiaryRecords::select('diary_id', 'order_no', 'ship_to_name', 'ship_to_post_code','ship_to_city',
                        'type_of_supply_code', 'order_weight','order_amount','location_code', 'shipping_agent_code', 'shipment_type', 'promised_delivery_date',
                        'delivery_confirmed', 'updated_at', 'balance_amount','ship_status','dispatch_requested_date')
                        ->where('shipping_agent_service_code','=','POST')
                        ->whereBetween('promised_delivery_date',[$fromDate,$toDate])
                        ->orderBy('promised_delivery_date', 'ASC')
                        ->get();
            } else {
                $post = DiaryRecords::select('diary_id', 'order_no', 'ship_to_name', 'ship_to_post_code','ship_to_city',
                        'type_of_supply_code', 'order_weight','order_amount','location_code', 'shipping_agent_code', 'shipment_type', 'promised_delivery_date',
                        'delivery_confirmed', 'updated_at', 'balance_amount','ship_status','dispatch_requested_date')
                        ->where([
                            ['shipping_agent_service_code','=','POST'],
                            ['location_code','=',$selectedBranch]
                        ])
                        ->whereBetween('promised_delivery_date',[$fromDate,$toDate])
                        ->orderBy('promised_delivery_date', 'ASC')
                        ->get();
            }
            return $post;
        } catch (\Exception $e) {
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**Get courier data */
    public function getCourierData($selectedBranch, $fromDate, $toDate)
    {
        $this->error_msg = '';
        $courier = array();
        try {
            if ($selectedBranch == 'All') {
                $courier = DiaryRecords::select('diary_id', 'order_no', 'ship_to_name', 'ship_to_post_code','ship_to_city',
                            'type_of_supply_code', 'order_weight','order_amount','location_code', 'shipping_agent_code', 'shipment_type', 'promised_delivery_date',
                            'delivery_confirmed', 'updated_at', 'balance_amount','ship_status','dispatch_requested_date')
                        ->where('shipping_agent_service_code','=','COURIER')
                        ->whereBetween('promised_delivery_date',[$fromDate,$toDate])
                        ->orderBy('promised_delivery_date', 'ASC')
                        ->get();
            } else {
                $courier =  DiaryRecords::select('diary_id', 'order_no', 'ship_to_name', 'ship_to_post_code','ship_to_city',
                            'type_of_supply_code', 'order_weight','order_amount','location_code', 'shipping_agent_code', 'shipment_type', 'promised_delivery_date',
                            'delivery_confirmed', 'updated_at', 'balance_amount','ship_status','dispatch_requested_date')
                            ->where([
                                ['shipping_agent_service_code','=','COURIER'],
                                ['location_code','=',$selectedBranch]
                            ])
                            ->whereBetween('promised_delivery_date',[$fromDate,$toDate])
                            ->orderBy('promised_delivery_date', 'ASC')
                            ->get();
            }
            return $courier;
        } catch (\Exception $e) {
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**
     * Total value and weight for all runs in a van for single day
     */
    public function getEachDayTotalValueAndWt($vanNo, $startDate, $endDate)
    {
        try {
            return DB::connection('mysql')->table('diary')
                ->select('promised_delivery_date',
                    DB::raw('SUM(order_weight) as totalDayWeight'),
                    DB::raw('SUM(order_amount) as totalDayValue'))
                ->whereRaw("shipping_agent_service_code LIKE ? AND promised_delivery_date BETWEEN ? AND ?", array($vanNo . '%', $startDate, $endDate))
                ->groupBy('promised_delivery_date')->get();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**
     *
     */
    public function getEachDayTotalMileage($vanNo, $startDate, $endDate)
    {
        try {
            return DB::connection('mysql')->table('api_routing')
                ->select('promised_delivery_date',
                    DB::raw('SUM(time_seconds) as totalTravelTime'),
                    DB::raw('SUM(distance_miles) as totalJourneyDistance'))
                ->whereRaw("vehicle_id LIKE ? AND promised_delivery_date BETWEEN ? AND ?", array($vanNo . '%', $startDate, $endDate))
                ->groupBy('promised_delivery_date')->get();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**
     * Get total orders weight and amount for each van
     */
    public function totalWeekWeightAndValueForSingleVan($vanId, $dateFrom, $dateTo)
    {
        //print_r($vanId);
        try {

            return DB::connection('mysql')->table('diary as d')
                ->select('d.shipping_agent_service_code as van_number',
                    DB::raw('SUM(d.order_weight) as totalWeight'),
                    DB::raw('SUM(d.order_amount) as totalValue'))
                ->whereRaw("d.shipping_agent_service_code LIKE ? AND d.promised_delivery_date BETWEEN ? AND ?", array($vanId, $dateFrom, $dateTo))
                ->groupBy('d.shipping_agent_service_code')->get();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }
    /**
     * Get total orders weight and amount for each day in selected week.
     * @params selected branch id
     * location code
     * shipping agent code
     * selected week start and end date
     */
    public function totalWeekWeightAndValueForEachDay($selected_branch_id,$fromDate,$toDate)
    {
        //print_r($vanId);
        
        try {
            $service_list=['COURIER','COLLECTION','POST'];
            if($selected_branch_id!=0){
                $getBranchCode=$this->getBranchCode($selected_branch_id);
                $getSelectedBranchShippingCode=$this->getBranchShippingCode($selected_branch_id);
                array_push($service_list,$getSelectedBranchShippingCode);
                print_r($service_list);
                return DiaryRecords::select('shipping_agent_code as shipping_service',
                        'promised_delivery_date as date',
                        DB::raw('COALESCE(SUM(order_weight), 0) as totalWeightPerDay'),
                        DB::raw('COALESCE(sum(order_amount), 0) as totalValuePerDay'))
                        ->whereBetween('promised_delivery_date',[$fromDate,$toDate])
                        ->where('location_code','=',$getBranchCode)
                        ->whereIn('shipping_agent_code',$service_list)
                        ->groupBy('shipping_agent_code','promised_delivery_date')
                        ->orderBy('shipping_agent_code','ASC')
                        ->get();
            }else{
               // $getAllBranchShippingCodes=$this->getAllBranchShippingCodes();
               // $service_list=array_merge($service_list,$getAllBranchShippingCodes);
                return DiaryRecords::select('shipping_agent_code as shipping_service',
                        'promised_delivery_date as date',
                        DB::raw('COALESCE(SUM(order_weight), 0) as totalWeightPerDay'),
                        DB::raw('COALESCE(sum(order_amount), 0) as totalValuePerDay'))
                        ->whereBetween('promised_delivery_date',[$fromDate,$toDate])
                        ->whereIn('shipping_agent_code',$service_list)
                        ->where('shipping_agent_code','LIKE','DH%')
                        ->groupBy('shipping_agent_code','promised_delivery_date')
                        ->orderBy('shipping_agent_code','ASC')
                        ->get();
                
            }
           
            
            // return DB::connection('mysql')->table('diary as d')
            //     ->select('d.shipping_agent_code as shipping_service',
            //         DB::raw('COALESCE(SUM(d.order_weight), 0) as totalWeightPerDay'),
            //         DB::raw('COALESCE(sum(d.order_amount), 0) as totalValuePerDay'))
            //     ->whereRaw("d.s LIKE ? AND d.promised_delivery_date BETWEEN ? AND ?", array($vanId, $dateFrom, $dateTo))
            //     ->groupBy('d.shipping_agent_service_code')->get();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
    }

    /*New orders refresh based on large refresh cookie value*/
    public function checkOrderUpdates(Request $request)
    {
        //Cache::flush();
        $new_orders = '';
        try {
            $tableName = "diary";
            $lastRefresh = $request->cookieValue;
            $selected_branch_id = $request->selectedBranch;
            $selectedDate = $request->selectedDate;
            $selectedDate = date('Y-m-d', strtotime($selectedDate));

            $newSelectedDateFormat = new Carbon($selectedDate);
            $weekStartDate = $newSelectedDateFormat->startOfWeek()->format('Y-m-d');
            $weekEndDate = $newSelectedDateFormat->endOfWeek()->format('Y-m-d');
            //$weekNumber=$newSelectedDateFormat->weekOfYear;
            $interval_period = CarbonPeriod::create($weekStartDate, $weekEndDate);

            if ($selected_branch_id != 0) {
                //Get location code with branch_id
                $getBranchCode = $this->getBranchCode($selected_branch_id);
                //Get Data from Nav and Update the Tables
                $getNavData = $this->_getSetNavData($getBranchCode, $weekStartDate, $weekEndDate);
                //$getNavData = $this->bcNAVRequest($getBranchCode, $weekStartDate, $weekEndDate);
                //print_r($getNavData);
                
                // DB::connection('mysql')->table($tableName)
                //     ->upsert($getNavData, 'order_no');
                DiaryRecords::upsert($getNavData, 'order_no');
            } else {
                $countofBranches = $this->getCountOfBranches();
                for ($i = 1; $i <= $countofBranches; $i++) {
                    $getBranchCode = $this->getBranchCode($i);
                    if($getBranchCode!=''){
                        $getNavData = $this->_getSetNavData($getBranchCode, $weekStartDate, $weekEndDate);
                        //$getNavData = $this->bcNAVRequest($getBranchCode, $weekStartDate, $weekEndDate);
                        DiaryRecords::upsert($getNavData, 'order_no');
                    }
                }
            }
            $exclude = ['COURIER', 'POST', 'COLLECTION'];
            $new_orders = DB::connection('mysql')->table('diary as d')
                ->select('d.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 'd.order_weight',
                    'd.order_amount', 'd.currency_code', 'd.location_code', 'd.shipping_agent_code', 'd.shipping_agent_service_code', 'd.shipment_type', 'd.promised_delivery_date',
                    'd.delivery_confirmed', 'd.last_update', 'd.balance_amount', 'd.last_shipping_no', 'd.ship_status', 'd.completed', 'd.dispatch_requested_date')
                ->whereRaw("d.last_update > ? AND d.location_code=? AND d.promised_delivery_date BETWEEN ? AND ?", array($lastRefresh, $getBranchCode, $weekStartDate, $weekEndDate))
                ->whereNotIn("d.shipping_agent_service_code", array('COURIER', 'POST', 'COLLECTION'))
                ->get();
        } catch (\Exception $e) {
            $this->error_msg = "error";
            die("Could not connect to the database. Please check your configuration. error:" . $e);
        }
        return response()->json([
            'error_msg' => $this->error_msg,
            'new_orders' => $new_orders
        ]);
    }
    public function getDataFromViewAction(Request $request)
    {
        $selected_branch_id = $request->selectedBranch;
        //$selected_branch_id=0;
        $selectedDate = $request->selectedDate;
        $selectedDate = date('Y-m-d', strtotime($selectedDate));
        $newSelectedDateFormat = new Carbon($selectedDate);
        $weekStartDate = $newSelectedDateFormat->startOfWeek()->format('Y-m-d');
        $weekEndDate = $newSelectedDateFormat->endOfWeek()->format('Y-m-d');
        // $weekStartDate='2023-01-02';
        // $weekEndDate='2023-01-14';
        // $getBranchCode='BASINGSTOK';
        
        return $this->callAPIRequestForOrders($selected_branch_id, $weekStartDate, $weekEndDate);
    }
    /*get data from view branch id,selected date*/
   
    public function callAPIRequestForOrders($selected_branch_id, $weekStartDate, $weekEndDate)
    {

        $this->error_msg='';
        try{
            $interval_period = CarbonPeriod::create($weekStartDate, $weekEndDate);
            $exclude = ['COURIER', 'POST', 'COLLECTION'];
            /**Get selected week comments */
            $getComments=$this->getCommentsIndependently($weekStartDate, $weekEndDate);
            $getBranchList=$this->getAllBranchDetails();
            /**Get selected van details */
            $selectedBranchVanNumbers = $this->getVanDetails($selected_branch_id,$weekStartDate);
            $selectedWeekVansWithoutRuns=$this->getDistinctVanDetails($selected_branch_id,$weekStartDate);
            $weeklyWeightAndValueForSingleVan = array();
            foreach ($selectedBranchVanNumbers as $key => $value) {
                $weeklyWeightAndValueForSingleVan[$value->shipping_agent_service_code] = $this->totalWeekWeightAndValueForSingleVan($value, $weekStartDate, $weekEndDate);
            }
            /*selected branch*/
            if($selected_branch_id!=0){
                /**get bookable status details for selected week and branch */
                $getBookableValues = $this->getBookableStatus($selected_branch_id, $weekStartDate, $weekEndDate);
                
                //Get location code with branch_id
                $getBranchCode = $this->getBranchCode($selected_branch_id);
                //$getTotalWtAndValuePerDay=$this->totalWeekWeightAndValueForEachDay($selected_branch_id,$weekStartDate,$weekEndDate);
                //print_r($getTotalWtAndValuePerDay);
                /**Collection orders */
                $collectionData = $this->getCollectionData($getBranchCode, $weekStartDate, $weekEndDate);
                /**Post Orders */
                $postData = $this->getPostData($getBranchCode, $weekStartDate, $weekEndDate);
                /**Courier Orders */
                $courierData = $this->getCourierData($getBranchCode, $weekStartDate, $weekEndDate);

                /**VAN Orders */
                $getNavData = $this->_getSetNavData($getBranchCode, $weekStartDate, $weekEndDate);
                //$getNavData=$this->bcNAVRequest($getBranchCode, $weekStartDate, $weekEndDate);
                /**Compare nav and diary data and modify the diary data if any changes required using upsert
                 * Here promised_delivery_date,location_code,shipping_agent_service_code ares index key
                 * Order_no is unique_key
                 * 
                 */
                $getDiaryData=DiaryRecords::where('location_code','=',$getBranchCode)
                ->whereBetween('promised_delivery_date',[$weekStartDate,$weekEndDate])
                ->get();
                
                $finalDiaryData=json_decode(json_encode($getDiaryData), true);
                /**Remove if any order not exist in nav data */
                foreach($finalDiaryData as $key=>$dbData){
                    $order_status=false;
                    foreach($getNavData as $key2=>$itemInNav){
                        if($dbData['order_no']==$itemInNav['order_no']){
                            $order_status=true;
                            break;
                        }
                    }
                    if($order_status==false){
                        $deleteRecord=DiaryRecords::where('order_no',$dbData["order_no"])->delete();
                    }
                }
                DiaryRecords::upsert($getNavData, 'order_no');
                /**Now fetch final diary table data for selected branch and week */
                $getSelectedVehicleData = Vehicle::from('vehicle as v')
                ->join('diary as d', 'v.shipping_agent_service_code', 'd.shipping_agent_service_code')
                ->select('v.shipping_agent_service_code as van_number',
                    'v.delivery_capacity', 'v.target_amount', 'v.registration_number', 'v.vehicle_type',
                    'd.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 
                    'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 
                    'd.order_weight','d.order_amount', 'd.currency_code', 'd.location_code', 
                    'd.shipping_agent_code', 'd.shipment_type', 'd.promised_delivery_date',
                    'd.delivery_confirmed', 'd.last_update', 'd.balance_amount', 'd.last_shipping_no', 
                    'd.ship_status', 'd.completed', 'd.dispatch_requested_date',
                    DB::raw("(SELECT vdc.comments FROM vehicle_diary_comments vdc  WHERE 
                    vdc.vehicle_id = v.vehicle_id AND vdc.vehicle_diary_date = d.promised_delivery_date) AS comments"))
                ->where('location_code','=',$getBranchCode)
                ->whereBetween('promised_delivery_date',[$weekStartDate,$weekEndDate])
                ->whereNotIn('d.shipping_agent_service_code', $exclude)
                ->orderBy('d.promised_delivery_date', 'ASC')
                ->orderBy('d.shipping_agent_service_code', 'ASC')->get();
                
            }
            /*All branches*/
            else{
               
                /**get bookable status details for selected week and all branches */
                $getBookableValues = $this->getBookableStatus(0, $weekStartDate, $weekEndDate);
                /**Collection orders */
                $collectionData = $this->getCollectionData('All', $weekStartDate, $weekEndDate);
                /**Post Orders */
                $postData = $this->getPostData('All', $weekStartDate, $weekEndDate);
                /**Courier Orders */
                $courierData = $this->getCourierData('All', $weekStartDate, $weekEndDate);

                $getTotalWtAndValuePerDay=$this->totalWeekWeightAndValueForEachDay($selected_branch_id,$weekStartDate,$weekEndDate);
               // print_r($getTotalWtAndValuePerDay);
                $getBranchIds = $this->getBranchIdsList();
                
                $getNavData ='';
                foreach($getBranchIds as $branch_id){
                    $getBranchCode = $this->getBranchCode($branch_id);
                    $getNavData = $this->_getSetNavData($getBranchCode, $weekStartDate, $weekEndDate);
                    //$getNavData=$this->bcNAVRequest($getBranchCode, $weekStartDate, $weekEndDate);
                    /**Compare nav and diary data and modify the diary data if any changes required using upsert
                     * Here promised_delivery_date,location_code,shipping_agent_service_code ares index key
                     * Order_no is unique_key
                     * 
                     */
                    $getDiaryData=DiaryRecords::where('location_code','=',$getBranchCode)
                    ->whereBetween('promised_delivery_date',[$weekStartDate,$weekEndDate])
                    ->get();
                    $finalDiaryData=json_decode(json_encode($getDiaryData), true);
                    /**Remove if any order not exist in nav data */
                    foreach($finalDiaryData as $key=>$dbData){
                        $order_status=false;
                        foreach($getNavData as $key2=>$itemInNav){
                            if($dbData['order_no']==$itemInNav['order_no']){
                                $order_status=true;
                                break;
                            }
                        }
                        if($order_status==false){
                            $deleteRecord=DiaryRecords::where('order_no',$dbData["order_no"])->delete();
                        }
                    }
                    DiaryRecords::upsert($getNavData, 'order_no');
                }
                /**Now fetch final diary table data for all branches and selected week */
                $getSelectedVehicleData = Vehicle::from('vehicle as v')
                ->join('diary as d', 'v.shipping_agent_service_code', 'd.shipping_agent_service_code')
<<<<<<< HEAD:app/Http/Controllers/DespatchDiaryController.php
                ->select('v.shipping_agent_service_code as van_number',
                    'v.delivery_capacity', 'v.target_amount', 'v.registration_number', 'v.vehicle_type',
                    'd.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 
                    'd.ship_to_county', 'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 
                    'd.order_weight','d.order_amount', 'd.currency_code', 'd.location_code', 
                    'd.shipping_agent_code', 'd.shipment_type', 'd.promised_delivery_date',
                    'd.delivery_confirmed', 'd.updated_at', 'd.balance_amount', 'd.last_shipping_no', 
                    'd.ship_status', 'd.completed', 'd.dispatch_requested_date',
                    DB::raw("(SELECT vdc.comments FROM vehicle_diary_comments vdc  WHERE 
                    vdc.vehicle_id = v.vehicle_id AND vdc.vehicle_diary_date = d.promised_delivery_date) AS comments"))
                ->whereBetween('promised_delivery_date',[$weekStartDate,$weekEndDate])
                ->whereNotIn('d.shipping_agent_service_code', $exclude)
=======
                ->select('v.shipping_agent_service_code as van_number', 'v.vehicle_bookable', 'v.collapse_status',
                        'v.delivery_capacity', 'v.target_amount', 'v.registration_number', 'v.vehicle_type',
                        'd.diary_id', 'd.order_no', 'd.ship_to_name', 'd.ship_to_post_code', 'd.ship_to_county', 
                        'd.ship_to_city', 'd.ship_to_region_code', 'd.type_of_supply_code', 'd.order_weight',
                        'd.order_amount', 'd.currency_code', 'd.location_code', 'd.shipping_agent_code', 
                        'd.shipment_type', 'd.promised_delivery_date','d.delivery_confirmed', 'd.last_update', 
                        'd.balance_amount', 'd.last_shipping_no', 'd.ship_status', 'd.completed', 'd.dispatch_requested_date',
                        DB::raw("(SELECT vdc.comments FROM vehicle_diary_comments vdc  WHERE 
                        vdc.vehicle_id = v.vehicle_id AND vdc.vehicle_diary_date = d.promised_delivery_date) AS comments"))
                ->whereRaw("d.promised_delivery_date BETWEEN ? AND ?", array($weekStartDate, $weekEndDate))
>>>>>>> c96b118f9143e2c01992818c809d85c0737fe016:app/Http/Controllers/TestNavANDDiaryController.php
                ->orderBy('d.promised_delivery_date', 'ASC')
                ->orderBy('d.shipping_agent_service_code', 'ASC')->get();
            
                
            }

            //combine collection and post data
            $collectionPostData = array_merge($collectionData->toArray(), $postData->toArray());

            $dayWeightAndValueForAllVans = array();
            $collectionValueBooked = 0.0;
            $collectionBookedWeight = 0.0;
            $postValueBooked = 0.0;
            $postBookedWeight = 0.0;
            $courierValueBooked = 0.0;
            $courierBookedWeight = 0.0;


            /**
             * It will get each day total weight and value for Courier,Post,Collection and Vans.
             * Also entire selected week orders for collection,post and courier with weight and value.
             */
            foreach ($interval_period as $date) {
                $totalValue = 0.0;
                $totalWeight = 0.0;
                $totalWtPostColl = 0.0;
                $totalValuePostColl = 0.0;
                $totalCourierValue = 0.0;
                $totalCourierWeight = 0.0;
                $orders_date = $date->format('l');
                $selectedOrdersDate = $date->format('d-m-Y');
                $dummy_array = array();
                /*All vehicles for one day total weight and value*/
                foreach ($getSelectedVehicleData as $key2 => $value1) {
                    if ($date == $value1->promised_delivery_date) {
                        $totalWeight += $value1->order_weight;
                        $totalValue += $value1->order_amount;

                    }
                }
                $dayWeightAndValueForAllVans[$selectedOrdersDate]['weight'] = number_format($totalWeight, 2, '.', '');
                $dayWeightAndValueForAllVans[$selectedOrdersDate]['value'] = number_format($totalValue, 2, '.', '');
                /*All collection and post orders for one day total weight and value*/
                foreach ($collectionPostData as $key2 => $value1) {
                    
                    if ($date->format('Y-m-d 00:00:00') == $value1['promised_delivery_date']) {
                        $totalWtPostColl += $value1['order_weight'];
                        $totalValuePostColl += $value1['order_amount'];
                    }
                }
                $dayWeightAndValueForAllVans[$selectedOrdersDate]['weightPostCollection'] = $totalWtPostColl;
                $dayWeightAndValueForAllVans[$selectedOrdersDate]['valuePostCollection'] = $totalValuePostColl;
                /*Collection data*/
                $collectionDataForSelectedWeek[$orders_date] = '';
                foreach ($collectionData as $key => $value1) {
                    if ($date == $value1->promised_delivery_date) {
                        array_push($dummy_array, $value1);
                        $collectionValueBooked += $value1->order_amount;
                        $collectionBookedWeight += $value1->order_weight;

                    }
                }
                $dummy_array['date'] = $selectedOrdersDate;
                $collectionDataForSelectedWeek[$orders_date] = $dummy_array;
                /*Post data*/
                $dummy_array = array();
                $postDataForSelectedWeek[$orders_date] = '';
                foreach ($postData as $key => $value1) {
                    if ($date == $value1->promised_delivery_date) {
                        array_push($dummy_array, $value1);
                        $postValueBooked += $value1->order_amount;
                        $postBookedWeight += $value1->order_weight;
                    }
                }
                $dummy_array['date'] = $selectedOrdersDate;
                $postDataForSelectedWeek[$orders_date] = $dummy_array;
                /*All courier orders for one day total weight and value*/
                $dummy_array = array();
                $courierDataForSelectedWeek[$orders_date] = '';
                foreach ($courierData as $key2 => $value1) {
                    if ($date == $value1->promised_delivery_date) {
                        $totalCourierWeight += $value1->order_weight;
                        $totalCourierValue += $value1->order_amount;
                        array_push($dummy_array, $value1);
                        $courierValueBooked += $value1->order_amount;
                        $courierBookedWeight += $value1->order_weight;
                    }
                }
                $dummy_array['date'] = $selectedOrdersDate;
                $courierDataForSelectedWeek[$orders_date] = $dummy_array;
                $dayWeightAndValueForAllVans[$selectedOrdersDate]['weightCourier'] = $totalCourierWeight;
                $dayWeightAndValueForAllVans[$selectedOrdersDate]['valueCourier'] = $totalCourierValue;
                
                $collectionDataForSelectedWeek["collectionValueBooked"] = $collectionValueBooked;
                $collectionDataForSelectedWeek["collectionBookedWeight"] = $collectionBookedWeight;
                $postDataForSelectedWeek["postValueBooked"] = $postValueBooked;
                $postDataForSelectedWeek["postBookedWeight"] = $postBookedWeight;
                $courierDataForSelectedWeek["courierValueBooked"] = $courierValueBooked;
                $courierDataForSelectedWeek["courierBookedWeight"] = $courierBookedWeight;
            }
            
            /**
            * All Orders for selected week
            */
            $allOrdersForSelectedWeek = array();
            //print_r($getSelectedVehicleData);
            foreach ($selectedBranchVanNumbers as $key => $value) {
                foreach ($interval_period as $date) {
                    $finalDate = $date->format('l');
                    //print_r($getSelectedVehicleData);
                    foreach ($getSelectedVehicleData as $key1 => $value1) {
                        if ($value->shipping_agent_service_code == $value1->van_number) {
                            $allOrdersForSelectedWeek[$value->shipping_agent_service_code][$finalDate] = "";
                            
                        }
                    }
                    $allOrdersForSelectedWeek[$value->shipping_agent_service_code]["registration_number"] = "";
                    $allOrdersForSelectedWeek[$value->shipping_agent_service_code]["hideOrShowVehicle"] = "";
                    $allOrdersForSelectedWeek[$value->shipping_agent_service_code]["totalWeight"] = "";
                    $allOrdersForSelectedWeek[$value->shipping_agent_service_code]["totalValue"] = "";
                }
            }
            //print_r($allOrdersForSelectedWeek);

            // /**For selected branch */
            if ($selected_branch_id != 0) {
                /** Get selected branch post code,latitude and longitude */
                $selected_branch_postcode = $this->getSelectedBranchPostCode($selected_branch_id);
                $selectedBranchDetails = $this->getBranchLatAndLong($selected_branch_id);
                $branchLatLong = $selectedBranchDetails[0]->latitude . ',' . $selectedBranchDetails[0]->longitude;
                foreach ($selectedBranchVanNumbers as $key => $vannumbers) {
                    // For Each Van
                    $targetAmount = 0;
                    $targetWeight = 0;
                    foreach ($interval_period as $date) {
                        // For each Day
                        $check2 = array();
                        $api_routing = array();
                        $additional_data = array();
                        $originalSequence = array();
                        $optimizeRouteSequence=array();
                        $displayOrdersPosition = array();
                        $toAddress = '';

                        $gMapPostCodesString = '';

                        // $postcodes = array(); 

                        $allOrderNos = array();
                        $valueBooked = 0;
                        $bookedWeight = 0;
                        $travelTimeInSeconds = 0;
                        $journeyDistanceInMiles = 0;
                        $api_status = false;
                        $i = 0;
                        $originalSequence[$i++] = $selected_branch_postcode;
                        $uniqueId = $selected_branch_id . '/' . $vannumbers->shipping_agent_service_code . '/' . $date->format('Y-m-d');
                        
                        foreach ($getSelectedVehicleData as $key1 => $order) {
                            if($vannumbers->shipping_agent_service_code == $order->van_number
                            AND $date->format('Y-m-d 00:00:00')==$order->promised_delivery_date){
                                $promised_delivery_date = $order->promised_delivery_date;
                                $allOrderNos[] = $order->order_no;
                                $valueBooked += $order->order_amount;
                                $bookedWeight += $order->order_weight;
                                $targetAmount = $order->target_amount;
                                $targetWeight = $order->delivery_capacity;
                                $check2[] = $order;
                                //print_r($order->ship_to_post_code);
                                $originalSequence[$i++] = $order->ship_to_post_code;
                                /**Get lat and long for order postcodes */
                                $orderLatAndLong = $this->sendPostcodeToApi(str_replace(' ', '', $order->ship_to_post_code));
                                //print_r($orderLatAndLong);
                                if ($orderLatAndLong != '') {
                                    $toAddress .= "&to=" . $orderLatAndLong;
                                    $gMapPostCodesString .= $order->ship_to_post_code."|";

                                } else {
                                    $toAddress = '';
                                // echo "wrong api";
                                }
                            }
                        
                        }
                        
                        //$originalSequence[$i++] = $selected_branch_postcode;
                        //print_r($originalSequence);
                        /**If  orders exist send api routing */

                        if($gMapPostCodesString!=''){
                            try{
                                $gMapRes = $this->getGmapsDistance($originalSequence);
                                print_r($gMapRes['routes'][0]);
                                // print_r($gMapPostCodesString);
                                // print_r('<br>');
                                
                            }catch( Exception $error){
                                print_r("Google Maps Error");
                                print_r($error);
                            }

                            
                            
                          //  $totalDuration=0;
                          //  $totalDistance=0;

                           // foreach($gMapRes['routes'][0]['legs'] as $data)
                           // {
                               // $totalDuration += (int)$data['duration']['value'];
                               // $totalDistance += (int)$data['distance']['value'];
                           // }
                           // $totalDuration = gmdate("H:i:s", $totalDuration);

                            //print_r([$totalDuration ,(($totalDistance/1000)/1.609)."Miles"]);
                        }
















                        if ($toAddress != '') {
                            /**check orders exist in api routing table,
                            *  if not send api request and insert data in table 
                            */

                            $order_nos = $this->getAPIOrderNumbers($uniqueId);
                            //print_r("unique id order numbers");
                           // print_r($order_nos);
                            $toAddress .= "&to=" . $branchLatLong;
                            $api_routing['promised_delivery_date'] = $promised_delivery_date;
                            $api_routing['unique_id'] = $uniqueId;
                            $api_routing['branch_id'] = $selected_branch_id;
                            $api_routing['order_nos'] = json_encode($allOrderNos);
                            $api_routing['original_route'] = serialize($originalSequence);
                            $api_routing['vehicle_id'] = $vannumbers->shipping_agent_service_code;
                            $this->processAPIRoutingDetails(
                                $api_status,
                                $travelTimeInSeconds,
                                $journeyDistanceInMiles,
                                $optimizeRouteSequence,
                                $displayOrdersPosition,
                                $order_nos,
                                $allOrderNos,
                                $uniqueId
                            );
                            //print_r($api_status);
                            if (!$api_status) {
                                $travelTime_journeyDist = $this->getMapQuestDistance($branchLatLong, $toAddress);
<<<<<<< HEAD:app/Http/Controllers/DespatchDiaryController.php
                                // $travelTimeAndDisance = $this->getGmapsDistance($originalSequence); 
                                // getGmapsDistance(); 
                                // implement GMaps here 

=======
                                //print_r($travelTime_journeyDist);
>>>>>>> c96b118f9143e2c01992818c809d85c0737fe016:app/Http/Controllers/TestNavANDDiaryController.php
                                if ($travelTime_journeyDist != "Error") {
                                    
                                    $travelTimeInSeconds = $travelTime_journeyDist['journeyTime'];
                                    $journeyDistanceInMiles = $travelTime_journeyDist['journeyDistance'];
                                    // $travelTimeInSeconds = $travelTimeAndDisance['journeyTime'];
                                    // $journeyDistanceInMiles = $travelTimeAndDisance['journeyDistance'];
                                    $optimizeRouteSequence = $travelTime_journeyDist['optimizeRouteSequence'];
                                    
                                    $api_routing['error_status'] = 0;
                                    
                                    /*To display orders position */
                                    foreach ($optimizeRouteSequence as $key => $routesequence) {
                                        if ($key > 0 && $key < (count($optimizeRouteSequence) - 1)) {
                                           // echo "route loop:".$key."--".$routesequence."\n";
                                            foreach ($originalSequence as $key1 => $postcodes) {
                                                $dummy = array();
                                                if ($key1 > 0 && $key1 < (count($originalSequence) - 1)) {
                                                    //echo "post codes loop:".$key1."--".$postcodes."\n";
                                                    if ( $routesequence == $key1) {
                                                        $displayOrdersPosition[$key] = $postcodes;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    
                                    $api_routing['optimized_route'] = serialize($optimizeRouteSequence);
                                    $api_routing['display_orders_position'] = serialize($displayOrdersPosition);
                                    $api_routing['time_seconds'] = $travelTimeInSeconds;
                                    $api_routing['distance_miles'] = number_format($journeyDistanceInMiles, 2, '.', '');
                                    $this->setAPIRoutingDetails($api_routing);
                                }
                            }
                        }
                        $additional_data["travelTime"] = $travelTimeInSeconds != 0 ? gmdate("H:i:s", $travelTimeInSeconds) : 0;
                        $additional_data["journeyDistance"] = number_format($journeyDistanceInMiles, 2, '.', '');
                        $additional_data['display_order_position'] = $displayOrdersPosition;




















                        /**Adding comments to end of each day for van */
                        $additional_data["comments"] = "";
                        foreach ($getComments as $value) {
                            if ($value->shipping_agent_service_code == $vannumbers->shipping_agent_service_code && $value->vehicle_diary_date == $date->format('Y-m-d')) {
                                $additional_data["comments"] = str_replace('&amp;', '&', $value->comments);
                            }
                            // if (Str::contains($vannumbers->shipping_agent_service_code,$value->shipping_agent_service_code) && $value->vehicle_diary_date == $date->format('Y-m-d')) {
                            //     $additional_data["comments"] = str_replace('&amp;', '&', $value->comments);
                            // }

                        }
                        /** Bookable status */
                        foreach ($getBookableValues as $key => $bookingStatus) {
                            if ($bookingStatus->shipping_agent_service_code == $vannumbers->shipping_agent_service_code && $bookingStatus->vehicle_booking_date == $date->format('Y-m-d')) {
                                $bookableStatus = $bookingStatus->vehicle_booking_status;
                                $additional_data["bookable_status"] = $bookableStatus;
                            }
                        }
                        $additional_data["value_booked"] = number_format($valueBooked, 2, '.', '');
                        $additional_data["to_book"] = number_format($targetAmount - $valueBooked, 2, '.', '');
                        $additional_data["booked_weight"] = number_format($bookedWeight, 2, '.', '');
                        $additional_data["remaining_weight"] = number_format($targetWeight - $bookedWeight, 2, '.', '');
                        $additional_data["date"] = $date->format('d-m-Y');
                        if(count($check2)>0){
<<<<<<< HEAD:app/Http/Controllers/DespatchDiaryController.php
                            //$key_values = array_column($check2, 'ship_to_post_code'); 
                            if(count($check2)==count($displayOrdersPosition))
                                array_multisort($check2,$displayOrdersPosition);
=======
                            // echo "display orders";
                            // print_r($displayOrdersPosition);
                            $key_values = array_column($check2, 'ship_to_post_code'); 
                            // echo "check key values";
                            // print_r($key_values);
                            if(count($check2)==count($displayOrdersPosition))
                                array_multisort($check2,$displayOrdersPosition);
                            //print_r($check2);
                        }
                        $allOrdersForSelectedWeek[$vannumbers][$finalDate] = array_merge($check2, $additional_data);
                    }
                    foreach ($allVanNumbers as $keydata => $van_details) {
                        if ($vannumbers == $van_details->shipping_agent_service_code) {
                            $allOrdersForSelectedWeek[$vannumbers]["registration_number"] = $van_details->registration_number;
                            $allOrdersForSelectedWeek[$vannumbers]["hideOrShowVehicle"] = $van_details->collapse_status;
>>>>>>> c96b118f9143e2c01992818c809d85c0737fe016:app/Http/Controllers/TestNavANDDiaryController.php
                        }
                        
                        $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code][$date->format('l')] = array_merge($check2, $additional_data);
                    }
                    $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code]["registration_number"] = $vannumbers->registration_number;
                    $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code]["hideOrShowVehicle"] = $vannumbers->collapse_status;
                   
                    foreach ($weeklyWeightAndValueForSingleVan as $key => $currentVan) {
                        foreach ($currentVan as $key3 => $data) {
                            if ($vannumbers->shipping_agent_service_code == $data->van_number) {
                                $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code]["totalWeight"] = $data->totalWeight;
                                $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code]["totalValue"] = $data->totalValue;
    
                            }
                        }
                    }
                }
            }


            /**For all branches */
           
            else{
                $countofBranches =$this->getDistinctCountOfBranches();
                for ($branchIdForAll = 1; $branchIdForAll <= $countofBranches; $branchIdForAll++) {
                    /** Get selected branch post code,latitude and longitude */
                    $selected_branch_postcode = $this->getSelectedBranchPostCode($branchIdForAll);
                    $selectedBranchDetails = $this->getBranchLatAndLong($branchIdForAll);
                    $selectedLocationCode = $this->getBranchCode($branchIdForAll);
                    if($selectedLocationCode!=''){
                        $branchLatLong = $selectedBranchDetails[0]->latitude . ',' . $selectedBranchDetails[0]->longitude;
                    }
                    foreach ($selectedBranchVanNumbers as $key => $vannumbers) {
                        if ($vannumbers->branch_id == $branchIdForAll) {
                            $targetAmount = 0;
                            $targetWeight = 0;
                            foreach ($interval_period as $date) {
                                $check2 = array();
                                $api_routing = array();
                                $additional_data = array();
                                $originalSequence = array();
                                $optimizeRouteSequence=array();
                                $displayOrdersPosition = array();
                                $toAddress = '';
                                $allOrderNos = array();
                                $valueBooked = 0;
                                $bookedWeight = 0;
                                $travelTimeInSeconds = 0;
                                $journeyDistanceInMiles = 0;
                                $api_status = false;
                                $i = 0;
                                $originalSequence[$i++] = $selected_branch_postcode;
                                $uniqueId = $branchIdForAll . '/' . $vannumbers->shipping_agent_service_code . '/' . $date->format('Y-m-d');
                                
                                foreach ($getSelectedVehicleData as $key1 => $order) {
                                    if($vannumbers->shipping_agent_service_code == $order->van_number
                                    AND $date->format('Y-m-d 00:00:00')==$order->promised_delivery_date){
                                        $promised_delivery_date = $order->promised_delivery_date;
                                        $allOrderNos[] = $order->order_no;
                                        $valueBooked += $order->order_amount;
                                        $bookedWeight += $order->order_weight;
                                        $targetAmount = $order->target_amount;
                                        $targetWeight = $order->delivery_capacity;
                                        $check2[] = $order;
                                        // print_r($order->ship_to_post_code);
                                        $originalSequence[$i++] = $order->ship_to_post_code;
                                        /**Get lat and long for order postcodes */
                                        $orderLatAndLong = $this->sendPostcodeToApi(str_replace(' ', '', $order->ship_to_post_code));
                                        //print_r($orderLatAndLong);
                                        if ($orderLatAndLong != '') {
                                            $toAddress .= "&to=" . $orderLatAndLong;
                                        } else {
                                            $toAddress = '';
                                        // echo "wrong api";
                                        }
                                    }
                                
                                }
                                
                                $originalSequence[$i++] = $selected_branch_postcode;
                                //print_r($originalSequence);
                                /**If  orders exist send api routing */
                                if ($toAddress != '') {
                                    /**check orders exist in api routing table,
                                    *  if not send api request and insert data in table 
                                    */
                                    $order_nos = $this->getAPIOrderNumbers($uniqueId);
                                    $toAddress .= "&to=" . $branchLatLong;
                                    $api_routing['promised_delivery_date'] = $promised_delivery_date;
                                    $api_routing['unique_id'] = $uniqueId;
                                    $api_routing['branch_id'] = $branchIdForAll;
                                    $api_routing['order_nos'] = json_encode($allOrderNos);
                                    $api_routing['original_route'] = serialize($originalSequence);
                                    $api_routing['vehicle_id'] = $vannumbers->shipping_agent_service_code;
                                    $this->processAPIRoutingDetails(
                                        $api_status,
                                        $travelTimeInSeconds,
                                        $journeyDistanceInMiles,
                                        $optimizeRouteSequence,
                                        $displayOrdersPosition,
                                        $order_nos,
                                        $allOrderNos,
                                        $uniqueId
                                    );
                                    if (!$api_status) {
                                        $travelTime_journeyDist = $this->getMapQuestDistance($branchLatLong, $toAddress);
                                        if ($travelTime_journeyDist != "Error") {
                                            
                                            // $travelTimeInSeconds = $travelTime_journeyDist['journeyTime'];
                                            // $journeyDistanceInMiles = $travelTime_journeyDist['journeyDistance'];
                                            $optimizeRouteSequence = $travelTime_journeyDist['optimizeRouteSequence'];
                                            $api_routing['error_status'] = 0;
                                            
                                            /*To display orders position */
                                            foreach ($optimizeRouteSequence as $key => $routesequence) {
                                                if ($key > 0 && $key < (count($optimizeRouteSequence) - 1)) {
                                                   // echo "route loop:".$key."--".$routesequence."\n";
                                                    foreach ($originalSequence as $key1 => $postcodes) {
                                                        $dummy = array();
                                                        if ($key1 > 0 && $key1 < (count($originalSequence) - 1)) {
                                                            //echo "post codes loop:".$key1."--".$postcodes."\n";
                                                            if ($key1 == $routesequence) {
                                                                $displayOrdersPosition[$key] = $postcodes;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            $api_routing['optimized_route'] = serialize($optimizeRouteSequence);
                                            $api_routing['display_orders_position'] = serialize($displayOrdersPosition);
                                            // $api_routing['time_seconds'] = $travelTimeInSeconds;
                                            // $api_routing['distance_miles'] = number_format($journeyDistanceInMiles, 2, '.', '');
                                            $this->setAPIRoutingDetails($api_routing);
                                        }
                                    }
                                }
                                // $additional_data["travelTime"] = $travelTimeInSeconds != 0 ? gmdate("H:i:s", $travelTimeInSeconds) : 0;
                                // $additional_data["journeyDistance"] = number_format($journeyDistanceInMiles, 2, '.', '');
                                $additional_data['display_order_position'] = $displayOrdersPosition;
                                /**Adding comments to end of each day for van */
                                $additional_data["comments"] = "";
                                foreach ($getComments as $value) {
                                    // if ($value->shipping_agent_service_code == $vannumbers['vannumber'] && $value->vehicle_diary_date == $commentCheckDate) {
                                        
                                    //     $additional_data["comments"] = str_replace('&amp;', '&', $value->comments);
                                    // }
                                    if (Str::contains($vannumbers->shipping_agent_service_code,$value->shipping_agent_service_code) && $value->vehicle_diary_date ==$date->format('Y-m-d')) {
                                        $additional_data["comments"] = str_replace('&amp;', '&', $value->comments);
                                    }
                                }
                                /** Bookable status */
                                foreach ($getBookableValues as $key => $bookingStatus) {
                                    if ($bookingStatus->shipping_agent_service_code == $vannumbers->shipping_agent_service_code && $bookingStatus->vehicle_booking_date == $date->format('Y-m-d')) {
                                        $bookableStatus = $bookingStatus->vehicle_booking_status;
                                        $additional_data["bookable_status"] = $bookableStatus;
                                    }
                                }
                                $additional_data["value_booked"] = number_format($valueBooked, 2, '.', '');
                                $additional_data["to_book"] = number_format($targetAmount - $valueBooked, 2, '.', '');
                                $additional_data["booked_weight"] = number_format($bookedWeight, 2, '.', '');
                                $additional_data["remaining_weight"] = number_format($targetWeight - $bookedWeight, 2, '.', '');
                                $additional_data["date"] = $date->format('d-m-Y');
                                if(count($check2)>0){
                                    //$key_values = array_column($check2, 'ship_to_post_code'); 
                                    if(count($check2)==count($displayOrdersPosition))
                                        array_multisort($check2,$displayOrdersPosition);
                                }
                                // if (count($displayOrdersPosition) == 0) {
                                //     $allOrdersForSelectedWeek[$vannumbers['vannumber']][$finalDate] = array_merge($check2, $additional_data);
                                // }
                                // else{
                                //     /**Sort post codes based on optimize route */
                                //     $sortArray = array();
                                //     foreach ($displayOrdersPosition as $index => $postcode) {
                                //         foreach ($check2 as $key => $value) {
                                //             if ($value->ship_to_post_code == $postcode) {
                                //                 $sortArray[$index] = $value;
                                //             }
        
                                //         }
                                //     }
                                //     $allOrdersForSelectedWeek[$vannumbers['vannumber']][$finalDate] = array_merge($sortArray, $additional_data);
                                // }
                               // print_r($additional_data);
<<<<<<< HEAD:app/Http/Controllers/DespatchDiaryController.php
                                $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code][$date->format('l')] = array_merge($check2, $additional_data);
=======
                               if(count($check2)>0){
                                    // echo "display orders";
                                    // print_r($displayOrdersPosition);
                                    $key_values = array_column($check2, 'ship_to_post_code'); 
                                    // echo "check key values";
                                    // print_r($key_values);
                                    if(count($check2)==count($displayOrdersPosition))
                                        array_multisort($check2,$displayOrdersPosition);
                                    //print_r($check2);
                                }
                                $allOrdersForSelectedWeek[$vannumbers['vannumber']][$finalDate] = array_merge($check2, $additional_data);
                            }
                            
                            foreach ($allVanNumbers as $keydata => $van_details) {
                                
                                if ($vannumbers['vannumber'] == $van_details->shipping_agent_service_code) {
                                    $allOrdersForSelectedWeek[$vannumbers['vannumber']]["registration_number"] = $van_details->registration_number;
                                    $allOrdersForSelectedWeek[$vannumbers['vannumber']]["hideOrShowVehicle"] = $van_details->collapse_status;
                                }
>>>>>>> c96b118f9143e2c01992818c809d85c0737fe016:app/Http/Controllers/TestNavANDDiaryController.php
                            }
                            $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code]["registration_number"] = $vannumbers->registration_number;
                            $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code]["hideOrShowVehicle"] = $vannumbers->collapse_status;
                        
                            foreach ($weeklyWeightAndValueForSingleVan as $key => $currentVan) {
                                foreach ($currentVan as $key3 => $data) {
                                    if ($vannumbers->shipping_agent_service_code == $data->van_number) {
                                        $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code]["totalWeight"] = $data->totalWeight;
                                        $allOrdersForSelectedWeek[$vannumbers->shipping_agent_service_code]["totalValue"] = $data->totalValue;
            
                                    }
                                }
                            }
                        }
                    }
                }
            }
            /**Seggrate data with each Van with sub runs */
            $forallRuns = array();
            
            foreach ($selectedWeekVansWithoutRuns as $van_details) {
                
                $singleVan = array();
                $totalDayWtAndValueForAllRuns = array();
                
                foreach ($allOrdersForSelectedWeek as $key => $value) {
                    if (Str::contains($van_details->registration_number, $value["registration_number"])) {
                        $singleVan[$key] = $value;
                        //$singleVan=Arr::sort($singleVan);
                    }
                }
               
                $result = $this->getEachDayTotalValueAndWt($van_details->van_number, $weekStartDate, $weekEndDate);
                $getMileage = $this->getEachDayTotalMileage($van_details->van_number, $weekStartDate, $weekEndDate);

                foreach ($interval_period as $date) {
                    $setDate = $date->format('l');
                    $checkDate = $date->format('Y-m-d');
                    $data_status = false;
                    $data_status1 = false;
                    //if($setDate!='Sunday' || $setDate!='Saturday'){
                    foreach ($result as $key => $total) {

                        if ($total->promised_delivery_date == $date) {
                            $totalDayWtAndValueForAllRuns[$setDate]["totalDayWeight"] = $total->totalDayWeight;
                            $totalDayWtAndValueForAllRuns[$setDate]["totalDayValue"] = $total->totalDayValue;
                            $data_status = true;
                            break;
                        }
                    }
                    foreach ($getMileage as $key => $total) {

                        if ($total->promised_delivery_date == $checkDate) {

                            $totalDayWtAndValueForAllRuns[$setDate]["totalTravelTime"] = gmdate("H:i:s", $total->totalTravelTime);
                            $totalDayWtAndValueForAllRuns[$setDate]["totalJourneyDistance"] = $total->totalJourneyDistance;
                            $data_status1 = true;
                            break;
                        }
                    }

                    if ($data_status == false) {
                        $totalDayWtAndValueForAllRuns[$setDate]["totalDayWeight"] = 0.0;
                        $totalDayWtAndValueForAllRuns[$setDate]["totalDayValue"] = 0.0;

                    }
                    if ($data_status1 == false) {
                        $totalDayWtAndValueForAllRuns[$setDate]["totalTravelTime"] = 0.0;
                        $totalDayWtAndValueForAllRuns[$setDate]["totalJourneyDistance"] = 0.0;
                    }
                    //}

                }

                $forallRuns[$van_details->van_number] = $singleVan;
                $forallRuns[$van_details->van_number]["parent_collapse_status"] = $van_details->parent_collapse;
                $forallRuns[$van_details->van_number]["delivery_capacity"] = $van_details->delivery_capacity;
                $forallRuns[$van_details->van_number]["registration_number"] = $van_details->registration_number;
                $forallRuns[$van_details->van_number]["vehicle_type"] = $van_details->vehicle_type;
                $forallRuns[$van_details->van_number]["target_amount"] = $van_details->target_amount;
                $forallRuns[$van_details->van_number]['totalDayWtAndValue'] = $totalDayWtAndValueForAllRuns;

            }
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e);
            $this->error_msg='Could not connect to the database' . $e ;
        } 
        return response()->json([
<<<<<<< HEAD:app/Http/Controllers/DespatchDiaryController.php
=======
            //'getNavData'=>$getNavData,
>>>>>>> c96b118f9143e2c01992818c809d85c0737fe016:app/Http/Controllers/TestNavANDDiaryController.php
            'dairyData'=>$getSelectedVehicleData,
            'dayWeightAndValueForAllDeliveries' => $dayWeightAndValueForAllVans,
            'collectionOrdersForSelectedWeek' => $collectionDataForSelectedWeek,
            'postOrdersForSelectedWeek' => $postDataForSelectedWeek,
            'courierOrdersForSelectedWeek' => $courierDataForSelectedWeek,
            'vehicleOrdersWithRuns' => $forallRuns,
            'branch_list'=>$getBranchList,
            'selectedBranchVanNumbers'=>$selectedBranchVanNumbers,
            'allOrdersForSelectedWeek'=>$allOrdersForSelectedWeek,
            
        ]);
    }
     /**
     * Internal Method to get Data from nav, set the date and location to the query and return the array
     * @return type array
     *
     * NAV 2015 current data*/
    private function _getSetNavData($branchCode, $dateFrom, $dateTo)
    {
        /**
         * We are having one existing procedure to fetch the despatch orders in DunsterLiveDB database in NAV
         * Procedure Name: dbo.DeliverDiaryOrders
         * Inputs Params:
         * @FromDate ex:2023-03-22
         * @ToDate ex:2023-03-31
         * @Location ex:'BEDSBRANCH'
         */
        /*To call that procedure see the below query*
        * DB::connectin('sqlsrv')-establishing connection with mssql server to connect NAV DB
        */
        //print_r("Nav connection");
        $getDataFromNav = DB::connection('sqlsrv')->
        select('exec dbo.DeliveryDiaryOrders ?,?,?', array($dateFrom, $dateTo, $branchCode));
        $tempArray = array();
       // print_r($getDataFromNav);
        /**placing the nav order details in array to insert to diary table with table column fields/parameters */
        foreach ($getDataFromNav as $itemNav) {
            $result = array(
                //'diary_id' => NULL,
                'order_no' => $itemNav->{'No_'},
                'ship_to_name' => $itemNav->{'Ship-to Name'},
                'ship_to_post_code' => $itemNav->{'Ship-to Post Code'},
                'ship_to_county' => $itemNav->{'Ship-to County'},
                'ship_to_region_code' => $itemNav->{'Ship-to Country_Region Code'},
                'type_of_supply_code' => $itemNav->{'Type of Supply Code'},
                'order_weight' => $itemNav->{'Weight'},
                'order_amount' => $itemNav->{'Order Amount'},
                'currency_code' => $itemNav->{'Currency Code'},
                'location_code' => $itemNav->{'Location Code'},
                'shipping_agent_code' => $itemNav->{'Shipping Agent Code'},
                'shipping_agent_service_code' => $itemNav->{'Shipping Agent Service Code'},
                'shipment_type' => $itemNav->{'Shipment Type'},
                'promised_delivery_date' => date('Y-m-d', strtotime($itemNav->{'Promised Delivery Date'})),
                'delivery_confirmed' => $itemNav->{'Delivery Confirmed'},
                'balance_amount' => $itemNav->{'Balance Amount'},
                'last_shipping_no' => $itemNav->{'Last Shipping No_'},
                'ship_status' => $itemNav->{'ShipStatus'},
                'completed' => $itemNav->{'Completed'},
                'ship_to_city' => TRIM($itemNav->{'Ship-to City'}),
                'dispatch_change' => -1,
                'dispatch_requested_date' => date('Y-m-d', strtotime($itemNav->{'Dispatch Requested Date'}))
            );
            array_push($tempArray, $result);

        }
        $error_msg = "Error connecting to NAV MSSql";
        return $tempArray;
    }

    /**
     * processing MAPQuest API
     */
    private function processAPIRoutingDetails(&$api_status, &$travelTimeInSeconds, &$journeyDistanceInMiles, &$optimizeRouteSequence, &$displayOrdersPosition, $order_nos, $allOrderNos, $uniqueId)
    {
        if ($order_nos != '') {
            /**record exist but order no's mismatch call api request */
            if (sort($order_nos) != sort($allOrderNos)) {
                $api_status = false;
            } else {
                /**If order no's same get the required details */
                $api_routing_table = $this->getAPIRoutingDetails($uniqueId);
                if ($api_routing_table != '') {
                    $api_status = true;
                    $travelTimeInSeconds = $api_routing_table[0]->time_seconds;
                    $journeyDistanceInMiles = $api_routing_table[0]->distance_miles;
                    $optimizeRouteSequence = unserialize($api_routing_table[0]->original_route);
                    $displayOrdersPosition = unserialize($api_routing_table[0]->display_orders_position);
                } else {
                    /**else call again MapQuest API */
                    $api_status = false;
                }
            }
        }
        //return $api_status;
    }
    /** New BC NAV */
    public function bcNAVRequest($getBranchCode, $weekStartDate, $weekEndDate){
        /**Without handling single thread mechansim */
        try{
            $tempArray = array();
            $grant_type="client_credentials";
            $clientId="65602c4e-a6b4-4aaa-b7a2-c09acc338408";
            $clientSecret=".QJ8Q~XePlCMWoE1iAfNSDFjXlmzKuVlMa-nGaDb";
            $scope="https://api.businesscentral.dynamics.com/.default";
            $tenantID="2739a659-2d71-434e-9bcc-29f7df81ca59";
            $loginURL="https://login.microsoftonline.com/".$tenantID."/oauth2/v2.0/token";
            $response=Http::asForm()->post($loginURL,[
                'grant_type'=>$grant_type,
                'client_id'=>$clientId,
                'client_secret'=>$clientSecret,
                'scope'=>$scope
            ]);
            $responseResult=$response->json();
            //print_r($responseResult);
           
            if(isset($responseResult['error'])){
                print_r($responseResult['error']);
                
            }else{
                //print_r($responseResult);
                $access_token=$responseResult['access_token'];
                $token_type=$responseResult['token_type'];
                $expiry_time=$responseResult['expires_in'];
                $baseUrl = 'https://api.businesscentral.dynamics.com/v2.0/Dunsterhouse_QA/api/Dunsterhouse/webSite/v2.0/';
                $companyId = 'c7cc19cd-70e3-ed11-a7c7-000d3a8758a2';
                $endpoint = "companies($companyId)/deliveryDiary";
                $filter = "locationCode eq '$getBranchCode' and promisedDeliveryDate ge $weekStartDate and promisedDeliveryDate le $weekEndDate";
                //$encodedFilter = Str::of($filter)->replaceMatches('/[^a-zA-Z0-9]/', '');

                $url = $baseUrl . $endpoint . '?$filter=' . $filter;
                $response = Http::withOptions([
                    'timeout' => 60, // Increase timeout to 60 seconds
                ])->withHeaders([
                    'Authorization' => $token_type.' '.$access_token,
                ])->get($url);

                if ($response->successful()) {
                    $getDataFromNav = $response->json();
                    //print_r($data);
                    foreach ($getDataFromNav['value'] as $itemNav) {
                        $result = array(
                            //'diary_id' => NULL,
                            'order_no' => $itemNav['no'],
                            'ship_to_name' => $itemNav['shipToName'],
                            'ship_to_post_code' => $itemNav['shipToPostCode'],
                            'ship_to_county' => $itemNav['shipToCounty'],
                            'ship_to_region_code' => $itemNav['shipToCountryRegionCode'],
                            'type_of_supply_code' => $itemNav['typeOfSupplyCodeTNP'],
                            'order_weight' => $itemNav['Weight'],
                            'order_amount' => $itemNav['orderAmountTNP'],
                            'currency_code' => $itemNav['currencyCode'],
                            'location_code' => $itemNav['locationCode'],
                            'shipping_agent_code' => $itemNav['shippingAgentCode'],
                            'shipping_agent_service_code' => $itemNav['shippingAgentServiceCode'],
                            'shipment_type' => $itemNav['shipmentTypeTNP'],
                            'promised_delivery_date' => date('Y-m-d', strtotime($itemNav['promisedDeliveryDate'])),
                            'delivery_confirmed' => $itemNav['deliveryConfirmedTNP'],
                            'balance_amount' => $itemNav['balanceAmountTNP'],
                            'last_shipping_no' => $itemNav['lastShippingNo'],
                            'ship_status' => $itemNav['ShipStatus'],
                            'completed' => $itemNav['completedTNP'],
                            'ship_to_city' => TRIM($itemNav['shipToCity']),
                            'dispatch_change' => -1,
                            'dispatch_requested_date' => date('Y-m-d', strtotime($itemNav['dispatchRequestedDateTNP']))
                        );
                        array_push($tempArray, $result);
                    }
                    // Process the response data here
                } else {
                    // Handle the request error
                    $statusCode = $response->status();
                    $errorMessage = $response->body();
                    print_r("else");
                    print_r($errorMessage);
                    // Handle the error accordingly
                }
               // print_r($despatchDiaryAPIRequest);
            }
            
            
            return $tempArray;
        }catch(ClientException $e){
            print_r($e.getResponse());
        }
        /**Created promise request to handle single thread mechansim */
        // try{
        //     $grant_type="client_credentials";
        //     $clientId="65602c4e-a6b4-4aaa-b7a2-c09acc338408";
        //     $clientSecret=".QJ8Q~XePlCMWoE1iAfNSDFjXlmzKuVlMa-nGaDb";
        //     $scope="https://api.businesscentral.dynamics.com/.default";
        //     $tenantID="2739a659-2d71-434e-9bcc-29f7df81ca59";
        //     $loginURL="https://login.microsoftonline.com/".$tenantID."/oauth2/v2.0/token";
        //     $response=Http::asForm()->post($loginURL,[
        //         'grant_type'=>$grant_type,
        //         'client_id'=>$clientId,
        //         'client_secret'=>$clientSecret,
        //         'scope'=>$scope
        //     ]);
        //     $responseResult=$response->json();
          
        //     if(isset($responseResult['error'])){
        //         print_r($responseResult['error']);
                
        //     }else{
        //         $tempArray = array();
        //         $access_token=$responseResult['access_token'];
        //         $token_type=$responseResult['token_type'];
        //         $expiry_time=$responseResult['expires_in'];
        //         $baseUrl = 'https://api.businesscentral.dynamics.com/v2.0/Dunsterhouse_QA/api/Dunsterhouse/webSite/v2.0/';
        //         $companyId = 'c7cc19cd-70e3-ed11-a7c7-000d3a8758a2';
        //         $endpoint = "companies($companyId)/deliveryDiary";
        //         $filter = "locationCode eq '$getBranchCode' and promisedDeliveryDate ge $weekStartDate and promisedDeliveryDate le $weekEndDate";
               
        //         $url = $baseUrl . $endpoint . '?$filter=' . $filter;
        //         // Create an instance of the HTTP client
        //         $client = Http::withOptions([
        //             'timeout' => 60, // Increase timeout to 60 seconds
        //         ])->withHeaders([
        //             'Authorization' => $token_type.' '.$access_token,
        //         ]);

        //         print_r($client);
        //         // Create a Promise instance for the request
        //         $promise = $client->getAsync($url);

        //         // Execute the promise and handle the response
        //         $response = $promise->wait();
        //         print_r($response);
        //         print_r($response->sucessfull());
        //         if ($response->successful()) {
        //             $getDataFromNav = $response->json();
        //             print_r($getDataFromNav);
        //             foreach ($getDataFromNav['value'] as $itemNav) {
        //                 $result = array(
        //                     //'diary_id' => NULL,
        //                     'order_no' => $itemNav['no'],
        //                     'ship_to_name' => $itemNav['shipToName'],
        //                     'ship_to_post_code' => $itemNav['shipToPostCode'],
        //                     'ship_to_county' => $itemNav['shipToCounty'],
        //                     'ship_to_region_code' => $itemNav['shipToCountryRegionCode'],
        //                     'type_of_supply_code' => $itemNav['typeOfSupplyCodeTNP'],
        //                     'order_weight' => $itemNav['Weight'],
        //                     'order_amount' => $itemNav['orderAmountTNP'],
        //                     'currency_code' => $itemNav['currencyCode'],
        //                     'location_code' => $itemNav['locationCode'],
        //                     'shipping_agent_code' => $itemNav['shippingAgentCode'],
        //                     'shipping_agent_service_code' => $itemNav['shippingAgentServiceCode'],
        //                     'shipment_type' => $itemNav['shipmentTypeTNP'],
        //                     'promised_delivery_date' => date('Y-m-d', strtotime($itemNav['promisedDeliveryDate'])),
        //                     'delivery_confirmed' => $itemNav['deliveryConfirmedTNP'],
        //                     'balance_amount' => $itemNav['balanceAmountTNP'],
        //                     'last_shipping_no' => $itemNav['lastShippingNo'],
        //                     'ship_status' => $itemNav['ShipStatus'],
        //                     'completed' => $itemNav['completedTNP'],
        //                     'ship_to_city' => TRIM($itemNav['shipToCity']),
        //                     'dispatch_change' => -1,
        //                     'dispatch_requested_date' => date('Y-m-d', strtotime($itemNav['dispatchRequestedDateTNP']))
        //                 );
        //                 array_push($tempArray, $result);
        //             }
        //         } else {
        //             // Handle the request error
        //             $statusCode = $response->status();
        //             $errorMessage = $response->body();
        //             print_r("else");
        //             print_r($errorMessage);
        //             // Handle the error accordingly
        //         }
        //     }
        //     return $response;
        // }catch (Exception $e) {
        //     // Handle exceptions and errors
        //     $errorMessage = $e->getMessage();
        //     // Handle the error accordingly
        // }
    }

}
