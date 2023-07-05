<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Branch;

class BranchController extends Controller
{
    //
    private $error_msg='';
    public function getBranchDetails(){
        try {
            
            $branch_details= DB::connection('mysql')->table('branch')->get();
            return response()->json([
                'branch_details'=> $branch_details
            ]);
        } catch (\Exception $e) {
             die("Could not connect to the database. Please check your configuration. error:" . $e );
        }
    }
    public function getBranchDetailsById(Request $request){
        $getBranchRecord='';
        try{
            $getBranchRecord= Branch::where('branch_id',$request->branchId)->first();
            
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg='Could not connect to the database '.$e;
        }
        return response()->json([
            'error'=>$this->error_msg,
            'result'=>$getBranchRecord
        ]);  
    }

    public function deleteBranchDetailsById(Request $request){
        try{
            $id=$request->branchId;
            $deleteDBStatus=Branch::where('branch_id',$id)->delete();
            
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg='Could not connect to the database '.$e;
        }
        return response()->json([
            'error'=>$this->error_msg,
            'result'=>$deleteDBStatus
        ]);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateBranchDetailsById(Request $request){
        $updateBranch='';
        try{
            $id=$request->branchId;
            $form=$request->branchForm;
           
            $updateBranch=Branch::where('branch_id',$id)->update($form);
            
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg='Could not connect to the database '.$e;
        }
        return response()->json([
            'error'=>$this->error_msg,
            'result'=>$updateBranch
        ]);
    }

    public function createBranchDetails(Request $request){
        $createBranch='';
        $status='';
        try{
            $form=$request->branchForm;
            $getBranchList=Branch::all();
            foreach ($getBranchList as $branch_details){
                if($branch_details->{'shipping_agent_code'}==$form['shipping_agent_code']){
                    $status='exist';
                    break;
                }
            }
            
            if($status!='exist'){
                $createBranch=DB::connection('mysql')->table('branch')
                    ->insert($form);
            }
            
        }catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. error:" . $e );
            $this->error_msg='Could not connect to the database '.$e;
        }
        return response()->json([
            'error'=>$this->error_msg,
            'result'=>$createBranch
        ]);
    }
}
