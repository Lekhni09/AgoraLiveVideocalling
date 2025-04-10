<?php

use Illuminate\Support\Facades\Route;
use  Illuminate\Support\Facades\Auth;
use App\Models\MeetingEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/joinMeeting/{url?}', [App\Http\Controllers\MeetingController::class, 'joinMeeting'])->name('joinMeeting');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Auth::routes();
Route::get('/home', [App\Http\Controllers\MeetingController::class, 'meetingUser'])->name('meetingUser');
Route::get('/createMeeting', [App\Http\Controllers\MeetingController::class, 'createMeeting'])->name('createMeeting');
Route::get('/saveUserName', [App\Http\Controllers\MeetingController::class, 'saveUserName'])->name('saveUserName');
Route::get('/meetingApprove', [App\Http\Controllers\MeetingController::class, 'meetingApprove'])->name('meetingApprove');


Route::get('/check-host-meeting', function (Request $request) {
    // Check if the user is the host
    if (Session::has('meeting')) {
        return response()->json(['status' => true]); // Always true for host
    }

    // If not a host, check if random_user exists and status == 2
    $randomUserId = Session::get('random_user');
    $randomUser = MeetingEntry::where('random_user', $randomUserId)->first();

    if ($randomUser && $randomUser->status == 2) {
        return response()->json(['status' => true]); // Allow joining for receiver with status 2
    }

    return response()->json(['status' => false]); // Deny access otherwise
});



