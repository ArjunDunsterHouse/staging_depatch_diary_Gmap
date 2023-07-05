<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Models\User;

class LoginController extends Controller
{
    //
    /**
     * 
     * Login request
     * @param username, password String
     */

    private $error_msg='';
    public function login(Request $request){
         $login_details='';
         $user_details='';
        try{
            $username= $request->username;
            $password= md5($request->password);
            $login_status='Invalid';
            $getUserSession='';
           // print_r(gettype($user_details));
        //    $getBranchList= array (
        //     "0"=>array(
        //         'branch_id'=>0,
        //         'branch_location'=>'All'
        //     ));
        
           //$All=app('App\Http\Controllers\VehicleDiaryController')->getAllBranchDetails();
           $getBranchList=app('App\Http\Controllers\DespatchDiaryController')->getAllBranchDetails();
           //array_push($getBranchList,$All);
            $user_details=DB::connection('mysql')->table('admin_user as au')
            ->whereRaw("au.user_name = ?",array($username))->get();
           // print_r(gettype($user_details));
            if($user_details->isNotEmpty()){
                foreach($user_details as $data){
                  //  print_r($data);
                    if($password==$data->password){
                        $login_status='valid';
                        Session::flush();
                        
                        $getUserSession = Session::get('userid');
                        User::where('admin_user_id',$data->admin_user_id)->update([
                            'logged_status'=>'true'
                        ]);
                        
                    }
                    Session::push('login_details',[
                        'userid'=>$data->admin_user_id,
                        'username'=>$data->user_name,
                        'role'=>$data->role,
                        'logged_status'=>'true',
                        'status'=>$login_status
                    ]);
                    $login_details=Session::get('login_details');
                }
            }else{
                $this->error_msg="error";
            }
        }catch(\Exception $e){
            $this->error_msg="error" . $e;
            die("Could not connect to the database. Please check your configuration. error:" . $e );
        }
        return response()->json([
            'branch_list'=>$getBranchList,
            'error_msg'=>$this->error_msg,
            'login_details'=>$login_details,
            'user_details'=>$user_details
        ]);
        
    }
    public function logout(Request $request){
        $admin_user_id=$request->admin_user_id;
        $logged_status=$request->logged_status;
        $selected_branch=$request->selected_branch;
        if($logged_status=='true'){
            Session::flush();
            User::where('admin_user_id',$admin_user_id)->update([
                'logged_status'=>'false',
                'selected_branch'=>$selected_branch
            ]);
        }
        Session::put('logout','Inactive');
        $user_status=Session::get('logout');
        return $user_status;
    }
}
