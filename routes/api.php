<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Back\Accadamics\AdmissionTypeController;
use App\Http\Controllers\Back\Accadamics\CollegeController;
use App\Http\Controllers\Back\Accadamics\DepartmentController;
use App\Http\Controllers\Back\Accadamics\StudyLevelController;
use App\Http\Controllers\Back\Accadamics\YearController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::middleware('auth:api')->group(function(){
    Route::post('/logout',[AuthController::class,'logout']);
});
Route::middleware('auth:api')->group(function(){
    // user controller
    Route::get('/users' ,[UserController::class, 'index']);
    Route::post('/add-user',[UserController::class,'addUser']);
    Route::get('/delete-user/{id}' ,[UserController::class, 'deleteUser']);
    Route::get('/active-user/{id}' ,[UserController::class, 'changeStatus']);
    Route::get('/get-user/{id}' ,[UserController::class, 'showUser']);
    Route::post('/update-user/{id}',[UserController::class,'updateUser']);
    // role contoller
    Route::get('/roles' ,[RoleController::class, 'index']);
    Route::post('/add-role',[RoleController::class,'addRole']);
    Route::get('/delete-role/{id}' ,[RoleController::class, 'deleteRole']);
    Route::get('/get-role/{id}' ,[RoleController::class, 'showRole']);
    Route::post('/update-role/{id}',[RoleController::class,'updateRole']);
    //college controller
    Route::post('/add-college',[CollegeController::class,'addCollege']);
    Route::get('/delete-college/{id}' ,[CollegeController::class, 'deleteCollege']);
    Route::get('/get-college/{id}' ,[CollegeController::class, 'showCollege']);
    Route::post('/update-college/{id}',[CollegeController::class,'updateCollege']);
    //AdmissionType
    Route::post('/add-admissionType',[AdmissionTypeController::class,'addAdmissionType']);
    Route::get('/delete-admissionType/{id}' ,[AdmissionTypeController::class, 'deleteAdmissionType']);
    Route::get('/get-admissionType/{id}' ,[AdmissionTypeController::class,'showAdmissionType']);
    Route::post('/update-admissionType/{id}',[AdmissionTypeController::class,'updateAdmissionType']);
    //study level
    Route::post('/add-studyLevel',[StudyLevelController::class,'addStudyLevel']);
    Route::get('/delete-studyLevel/{id}' ,[StudyLevelController::class, 'deleteStudyLevel']);
    Route::get('/get-studyLevel/{id}' ,[StudyLevelController::class,'showStudyLevel']);
    Route::post('/update-studyLevel/{id}',[StudyLevelController::class,'updateStudyLevel']);
    //department
    Route::get('/departments',[DepartmentController::class,'index']);
    Route::post('/add-department',[DepartmentController::class,'addDepartment']);
    Route::get('/delete-department/{id}' ,[DepartmentController::class,'deleteDepartment']);
    Route::get('/get-department/{id}' ,[DepartmentController::class, 'showDepartment']);
    Route::post('/update-department/{id}',[DepartmentController::class,'updateDepartment']);
    //year
    Route::post('/add-year',[YearController::class,'addYear']);
    Route::get('/delete-year/{id}' ,[YearController::class,'deleteYear']);
    Route::get('/get-year/{id}' ,[YearController::class, 'showYear']);
    Route::post('/update-year/{id}',[YearController::class,'updateYear']);
    
});


// no auth
Route::get('/all-departments' ,[DepartmentController::class, 'allDept']);
Route::get('/colleges' ,[CollegeController::class, 'index']);
Route::get('/admissionTypes' ,[AdmissionTypeController::class, 'index']);
Route::get('/years',[YearController::class,'index']);
Route::get('/studyLevels' ,[StudyLevelController::class, 'index']);


