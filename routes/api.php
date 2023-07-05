<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\VehicleDiaryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\EmailController;
/**Test controller */
//use App\Http\Controllers\TestNavANDDiaryController;
use App\Http\Controllers\DespatchDiaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::group(['middleware' => ['auth', 'throttle:none']], function ($router) {

Route::post('send-email', [AdminController::class, 'sendEmailToken']);
Route::post('validate-token', [AdminController::class, 'validateEmailToken']);

//Route::post('latAndLong',[VehicleDiaryController::class,'getLatAndLong']);
//Route::post('notifications',[VehicleDiaryController::class,'checkOrderUpdates'] );
//Route::post('vehiclediary',[VehicleDiaryController::class,'getDataFromViewAction'] );
/**Test request */
//Route::post('vehiclediary',[TestNavANDDiaryController::class,'getDataFromViewAction'] );
Route::post('vehiclediary',[DespatchDiaryController::class,'getDataFromViewAction'] );
Route::post('notifications',[DespatchDiaryController::class,'checkOrderUpdates'] );
//Route::post('allbranches',[VehicleDiaryController::class,'getDataFromViewAction'] );
Route::post('updatecomments',[DespatchDiaryController::class,'sendCommentsToDB'] );
Route::post('updatebookable',[DespatchDiaryController::class,'sendBookableValueToDB'] );
Route::post('updateVehicleTabStatus',[DespatchDiaryController::class,'updateVehicleTabStatus']);
Route::post('parentCollapseForVehicle',[DespatchDiaryController::class,'updateParentCollapseForVehicle']);

//});

Route::post('vehicles',[VehicleController::class,'getVehicleTables'] );
Route::get('test',[VehicleController::class,'test'] );
Route::post('edit-vehicle',[VehicleController::class,'getVehicleDetailsById'] );
Route::post('update-vehicle',[VehicleController::class,'updateVehicleDetailsById'] );
Route::post('delete-vehicle',[VehicleController::class,'deleteVehicleDetailsById'] );
Route::post('create-vehicle',[VehicleController::class,'createVehicleDetails'] );
Route::post('check-van-validation',[VehicleController::class,'checkVanNo'] );


//Route::post('login',[AuthController::class,'login'] );
Route::post('login',[LoginController::class,'login'] );
Route::post('logout',[LoginController::class,'logout']);
Route::get('main', function () {
    return view('Home');
});

Route::post('users',[AdminController::class,'getAllUsersList'] );
Route::post('edit-user',[AdminController::class,'getUserDetailsById'] );
Route::post('update-user',[AdminController::class,'updateUserDetailsById'] );
Route::post('change-pwd',[AdminController::class,'updatePasswordById'] );
Route::post('reset-pwd',[AdminController::class,'resetPasswordByUsername'] );
Route::post('delete-user',[AdminController::class,'deleteUserDetailsById'] );
Route::post('create-user',[AdminController::class,'createUserDetails'] );

Route::get('branch',[BranchController::class,'getBranchDetails']);
Route::post('edit-branch',[BranchController::class,'getBranchDetailsById']);
Route::post('update-branch',[BranchController::class,'updateBranchDetailsById']);
Route::post('delete-branch',[BranchController::class,'deleteBranchDetailsById']);
Route::post('create-branch',[BranchController::class,'createBranchDetails']);


Route::get('/{any}',function(){
    return view('welcome');
})->where("any",".*");