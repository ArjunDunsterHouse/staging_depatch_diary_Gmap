<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use Mail;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMailable;

class AdminController extends Controller
{
    //
    private $error_msg='';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllUsersList(Request $request){
        try{
            $profile_status=$request->profileStatus;
            if($profile_status=='All')
                $admin_users=DB::connection('mysql')->table('admin_user')->get();
            else
                $admin_users=DB::connection('mysql')->table('admin_user')->whereRaw('profile_status =?',array($profile_status))->get();
            return response()->json([
                'userslist'=> $admin_users
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
    public function getUserDetailsById(Request $request){
        try{
            $id=$request->userId;
            $user_details=DB::connection('mysql')->table('admin_user')
            ->whereRaw('admin_user_id=?',array($id))
            ->first();
            
            return $user_details;
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
    public function updateUserDetailsById(Request $request){
        try{
            $id=$request->userId;
            $form=$request->userForm;
            $updateUser=DB::connection('mysql')->table('admin_user')->where('admin_user_id',$id)->update($form);
            return $updateUser;

        }catch (\Exception $e) {
            $error="Could not connect to the database. Please check your configuration. error:" . $e ;
        }
    }
    /**
     * Display the specified resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePasswordById(Request $request){
        try{
            //print_r($request->admin_user_id);
            
            $updatePwd=User::where('admin_user_id',$request->admin_user_id)->update([
                'password'=>md5($request->password)
            ]);
            
            return $updatePwd;

        }catch (\Exception $e) {
            $error="Could not connect to the database. Please check your configuration. error:" . $e ;
        }
    }
    public function resetPasswordByUsername(Request $request){
        $updatePwd='';
        $user_details='';
        try{
            $user_details=DB::connection('mysql')->table('admin_user')->where('email_id',$request->email)->value('admin_user_id');
            if($user_details!=''){
                $updatePwd=DB::connection('mysql')->table('admin_user')->where('admin_user_id',$user_details)->update(['password'=>md5($request->password)]);
            }
            else{
                $this->error_msg="User not exist";
            }
           
            //$updatePwd=DB::connection('mysql')->table('admin_user')->where('user_name',$request->username)->update(['password'=>md5($request->password)]);
            
            // User::where('user_name',$request->username)->update([
            //     'password'=>md5($request->password)
            // ]);
        }catch (\Exception $e) {
            //die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg='Could not connect to the database '.$e;
        }
        return response()->json([
            'error'=>$this->error_msg,
            'userid'=>$user_details,
            'username'=>$request->username,
            'result'=>$updatePwd
        ]);
    }
    public function deleteUserDetailsById(Request $request){
        try{
            $id=$request->userId;
            // $deleteDBStatus=DB::connection('mysql')->table('admin_user')->where('admin_user_id',$id)->update([
            //     'password'=>'blocked',
            //     'profile_status'=>'Inactive'
            // ]);
            $deleteDBStatus=User::where('admin_user_id',$id)->delete();
            return $deleteDBStatus;
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
        }
    }

    public function createUserDetails(Request $request){
        $error='0';
        $addUser='no';
        try{
            
            $form=$request->userForm;
            $form["password"]=md5($form["password"]);
            $availibity='False';
            $checkUserName=DB::connection('mysql')->table('admin_user')->get();
            foreach ($checkUserName as $user)
            {
                if($user->{'user_name'}==$form['user_name']){
                    $availibity='True';
                }
                
            }
            // print_r($availibity);
            // print_r($form['user_name']);
            if($availibity=='False'){
                $addUser=DB::connection('mysql')->table('admin_user')
                 ->insert($form);
            }
            else{
                $error='Duplicate user name found!';
            }
            // $addVehicle=DB::connection('mysql')->table('admin_user')
            // ->insert($form);
           
            // $getUserID=DB::connection('mysql')->getPdo()->lastInsertId();
           
            
           

        }catch (\Exception $e) {
            $error="Error:Could not connect to the database. Please check your configuration." . $e;
        }
        return response()->json([
            'error'=>$error,
            'result'=>$addUser
           // $form['user_name'],$checkUserName,$availibity
        ]);
    }
    public function sendEmailToken(Request $request){
        $error='';
        $token='';
        try{
            $user=User::where('email_id',$request->email)->first();
     
            if(!isset($user->admin_user_id)){
                $error='User email does not exist.';
                
            }else{
                $token=Str::random(32);
              // print_r($token);
                Mail::to($user->email_id)->send(new ResetPasswordMailable($token));
                $insertPasswortdTable=DB::connection('mysql')->table('password_reset')
                ->updateOrInsert(['user_email_id'=>$user->email_id],['token'=>$token,'attempts'=>0,'validation_status'=>'']);
            }
           
        }catch (Exception $e) {
            $error="Error:Could not connect to the database. Please check your configuration." . $e;
        }
        return response()->json([
            'error'=>$error,
            'token'=>$token
            //'result'=>$addUser
        ]);
        
    }
    /**
     * validate email token attempts
     */
    public function validateEmailToken(Request $request){
        $status='';
        $attempt=0;
        $error='';
        try{
            //print_r($request->email);
            $attempt=DB::connection('mysql')->table('password_reset')
            ->whereRaw('user_email_id =?',array($request->email))->value('attempts');
            if($attempt==0){
                $status='Only 2 attempts left.';
            }elseif($attempt==1){
                $status='Only 1 attempt left.';
            }else{
                $status='Token expired';
            }
            $validate_attempts=DB::connection('mysql')->table('password_reset')
            ->updateOrInsert(['user_email_id'=>$request->email],['attempts'=>++$attempt,'validation_status'=>$status]);
        }catch (\Exception $e) {
            $error="Error:Could not connect to the database. Please check your configuration." . $e;
        }
        return response()->json([
            'error'=>$error,
            'status'=>$status,
            'attemptValue'=>$attempt,
            'email'=>$request->email
            //'result'=>$addUser
        ]);
    }
}
