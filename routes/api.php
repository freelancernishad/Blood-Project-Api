<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationAuthController;

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

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });



// Route::post('/student/login', [StudentAuthController::class, 'login']);
// Route::post('/check/student/login', [StudentAuthController::class, 'checkTokenExpiration']);
// Route::middleware('auth:student')->group(function () {
    // Route::post('/student/logout', [StudentAuthController::class, 'logout']);
//     Route::get('/student/check-token', [StudentAuthController::class, 'checkToken']);

//     Route::get('/students/profile/{id}', [StudentController::class, 'show']);

//     // Add other protected routes specific to students here
// });




// Route::post('/students', [StudentController::class, 'store']);


// Route::group(['middleware' => 'auth:api'], function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     // Route::apiResource('students', StudentController::class);

//     Route::post('/students/set/ratings/{id}', [StudentController::class, 'setRating']);



//     Route::get('/students', [StudentController::class, 'index']);
//     Route::get('/students/{id}', [StudentController::class, 'show']);
//     Route::put('/students/{id}', [StudentController::class, 'update']);
//     Route::delete('/students/{id}', [StudentController::class, 'destroy']);

//     // Route::apiResource('questions', QuestionController::class);
//     // Route::apiResource('teachers', TeacherController::class);
//     // Route::apiResource('batches', BatchController::class);
//     // Route::apiResource('exams', ExamController::class);
// });




//// user auth
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/check/login', [AuthController::class, 'checkTokenExpiration'])->name('checklogin');
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Routes for user registration, update, delete, and show
    Route::prefix('users')->group(function () {
        Route::put('{id}', [UserController::class, 'update']);       // Update user by ID
        Route::delete('{id}', [UserController::class, 'delete']);    // Delete user by ID
        Route::get('{id}', [UserController::class, 'show']);          // Show user details by ID
    });

    Route::get('/user-access', function (Request $request) {
        return 'user access';
    });
});


//// organization auth
Route::post('/organization/login', [OrganizationAuthController::class, 'login']);
Route::post('/organization/check/login', [OrganizationAuthController::class, 'checkTokenExpiration']);
Route::post('/organization/check-token', [OrganizationAuthController::class, 'checkToken']);
Route::post('organizations/register', [OrganizationController::class, 'register']); // Organization registration

Route::group(['middleware' => ['auth:organization']], function () {
    Route::post('/organization/logout', [OrganizationAuthController::class, 'logout']);

    // Routes for organization registration, update, delete, and show
    Route::prefix('organizations')->group(function () {
        Route::put('{id}', [OrganizationController::class, 'update']);       // Update organization by ID
        Route::delete('{id}', [OrganizationController::class, 'delete']);    // Delete organization by ID
        Route::get('{id}', [OrganizationController::class, 'show']);          // Show organization details by ID
    });
    Route::post('organization/doners', [OrganizationController::class, 'getDonersByOrganization']);




    Route::get('/organization-access', function (Request $request) {
        return 'organization access';
    });
});



//// admin auth
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/check/login', [AdminAuthController::class, 'checkTokenExpiration']);
Route::get('/admin/check-token', [AdminAuthController::class, 'checkToken']);
Route::post('admin/create', [AdminController::class, 'create']);


Route::group(['middleware' => ['auth:admin']], function () {
    Route::post('admin/logout', [AdminAuthController::class, 'logout']);
    Route::get('/admin-access', function (Request $request) {
        return 'admin access';
    });
});



