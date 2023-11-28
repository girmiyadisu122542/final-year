<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Back\StudentController;
use App\Http\Controllers\Back\Accadamics\YearController;
use App\Http\Controllers\Back\Accadamics\CollegeController;
use App\Http\Controllers\Back\ProjectCoordinatorController;
use App\Http\Controllers\Back\Accadamics\DepartmentController;
use App\Http\Controllers\Back\Accadamics\StudyLevelController;
use App\Http\Controllers\Back\Accadamics\AdmissionTypeController;
use App\Http\Controllers\Back\AdvisorController;
use App\Http\Controllers\Back\AllBackendController;
use App\Http\Controllers\Back\DepartmentHeadController;
use App\Http\Controllers\Back\ResearcherController;
use App\Http\Controllers\Back\ResearchOfficeController;
use App\Http\Controllers\Front\FrontPageControler;

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
    Route::get('/get-users' ,[UserController::class, 'getUsers']);
    Route::get('/get-user/{id}' ,[UserController::class, 'showUser']);
    Route::post('/update-user/{id}',[UserController::class,'updateUser']);

    Route::get('/getUserRole' ,[UserController::class, 'getUserRole']);

    // role contoller
    Route::get('/roles' ,[RoleController::class, 'index']);
    Route::post('/add-role',[RoleController::class,'addRole']);
    Route::get('/delete-role/{id}' ,[RoleController::class, 'deleteRole']);
    Route::get('/get-role/{id}' ,[RoleController::class, 'showRole']);
    Route::get('/get-roles' ,[RoleController::class,'getRoles']);
    Route::post('/update-role/{id}',[RoleController::class,'updateRole']);
    //college controller
    Route::post('/add-college',[CollegeController::class,'addCollege']);
    Route::get('/delete-college/{id}' ,[CollegeController::class, 'deleteCollege']);
    Route::get('/get-college/{id}' ,[CollegeController::class, 'showCollege']);
    Route::get('/get-colleges' ,[CollegeController::class,'getColleges']);
    Route::post('/update-college/{id}',[CollegeController::class,'updateCollege']);
    //AdmissionType
    Route::post('/add-admissionType',[AdmissionTypeController::class,'addAdmissionType']);
    Route::get('/delete-admissionType/{id}' ,[AdmissionTypeController::class, 'deleteAdmissionType']);
    Route::get('/get-admissionType/{id}' ,[AdmissionTypeController::class,'showAdmissionType']);
    Route::post('/update-admissionType/{id}',[AdmissionTypeController::class,'updateAdmissionType']);
    Route::get('/get-admissiontypes' ,[AdmissionTypeController::class,'getAdmissionType']);
    
    //study level
    Route::post('/add-studyLevel',[StudyLevelController::class,'addStudyLevel']);
    Route::get('/delete-studyLevel/{id}' ,[StudyLevelController::class, 'deleteStudyLevel']);
    Route::get('/get-studyLevel/{id}' ,[StudyLevelController::class,'showStudyLevel']);
    Route::get('/get-studytypes' ,[StudyLevelController::class,'getStudyTypes']);
    Route::post('/update-studyLevel/{id}',[StudyLevelController::class,'updateStudyLevel']);
    //department
    Route::get('/departments',[DepartmentController::class,'index']);
    Route::post('/add-department',[DepartmentController::class,'addDepartment']);
    Route::get('/delete-department/{id}' ,[DepartmentController::class,'deleteDepartment']);
    Route::get('/get-department/{id}' ,[DepartmentController::class, 'showDepartment']);
    Route::get('/get-departments' ,[DepartmentController::class, 'getDepartments']);
    Route::post('/update-department/{id}',[DepartmentController::class,'updateDepartment']);
    //year
    Route::post('/add-year',[YearController::class,'addYear']);
    Route::get('/delete-year/{id}' ,[YearController::class,'deleteYear']);
    Route::get('/get-year/{id}' ,[YearController::class, 'showYear']);
    Route::get('/get-years' ,[YearController::class, 'getYears']);
    Route::post('/update-year/{id}',[YearController::class,'updateYear']);
    
    //department head 
    Route::get('/project_coordinators' ,[DepartmentHeadController::class, 'getProjectCoordinator']);
    Route::post('/add-project_coordinator',[DepartmentHeadController::class,'addProjectCoordinator']);
    Route::post('/update-project_coordinator/{id}',[DepartmentHeadController::class,'updateProjectCoordinator']);
    Route::get('/students' ,[DepartmentHeadController::class, 'getStudents']);
    Route::get('/get-student-document-deptHead' ,[DepartmentHeadController::class, 'getDocuments']);
    Route::post('/comment-on-document-deptHead' ,[DepartmentHeadController::class, 'addComment']);
    Route::get('/approve-document-deptHead/{id}' ,[DepartmentHeadController::class, 'approveDocument']);
    Route::get('/get-single-document-comment-deptHead/{id}' ,[DepartmentHeadController::class, 'getSingleDocumentComment']);
    Route::post('/update-comment-deptHead/{id}' ,[DepartmentHeadController::class, 'updateComment']);
    
    
    //project coodinator 
    Route::get('/advisors' ,[ProjectCoordinatorController::class, 'getAdvisor']);
    Route::post('/add-advisor',[ProjectCoordinatorController::class,'addAdvisor']);
    Route::post('/update-advisor/{id}',[ProjectCoordinatorController::class,'updateAdvisor']);
    Route::get('/get-student-document-proCoor' ,[ProjectCoordinatorController::class, 'getDocuments']);
    Route::post('/comment-on-document-proCoor' ,[ProjectCoordinatorController::class, 'addComment']);
    Route::get('/approve-document-proCoor/{id}' ,[ProjectCoordinatorController::class, 'approveDocument']);
    Route::get('/get-single-document-comment-proCoor/{id}' ,[ProjectCoordinatorController::class, 'getSingleDocumentComment']);
    Route::post('/update-comment-proCoor/{id}' ,[ProjectCoordinatorController::class, 'updateComment']);



     // advisor
     Route::get('/get-student-document' ,[AdvisorController::class, 'getDocument']);
     Route::post('/comment-on-document' ,[AdvisorController::class, 'addComment']);
     Route::get('/approve-document-advisor/{id}' ,[AdvisorController::class, 'approveDocument']);
     Route::get('/get-single-document-comment/{id}' ,[AdvisorController::class, 'getSingleDocumentComment']);
     Route::get('/show-comment/{id}' ,[AdvisorController::class, 'showComment']);
     Route::get('/delete-comment/{id}' ,[AdvisorController::class, 'deleteComment']);
     Route::post('/update-comment/{id}' ,[AdvisorController::class, 'updateComment']);
     
     
     // student 
     Route::get('/get-document' ,[StudentController::class, 'getDocument']);
     Route::get('/get-advisor' ,[StudentController::class, 'getAdvisor']);
     Route::post('/upload-document',[StudentController::class, 'uploadDocument']);
     Route::get('/show-document/{id}' ,[StudentController::class, 'showDocument']);
    Route::post('/update-document/{id}' ,[StudentController::class, 'updateDocument']);
    Route::get('/delete-document/{id}' ,[StudentController::class, 'deleteDocument']);
    Route::get('/view-document-comment/{id}' ,[StudentController::class, 'getSingleDocumentComment']);
    
    // research office 
    Route::get('/researchers' ,[ResearchOfficeController::class, 'getResearchers']);
    Route::get('/announcements' ,[ResearchOfficeController::class, 'getAnnouncements']);
    Route::post('/add-announcement' ,[ResearchOfficeController::class, 'addAnnouncement']);
    Route::get('/delete-announcment/{id}' ,[ResearchOfficeController::class, 'deleteAnnouncement']);
    Route::get('/show-announcment/{id}' ,[ResearchOfficeController::class, 'showAnnouncment']);
    Route::post('/update-announcement/{id}' ,[ResearchOfficeController::class, 'updateAnnouncment']);
    Route::get('/active-announcement/{id}' ,[ResearchOfficeController::class, 'changeStatus']);
    Route::get('/get-researcher-document' ,[ResearchOfficeController::class, 'getDocuments']);
    Route::get('/approve-document-researchOffice/{id}' ,[ResearchOfficeController::class, 'approveDocument']);
    Route::get('/get-researcher-proposal' ,[ResearchOfficeController::class, 'getProposals']);
    Route::get('/approve-proposal/{id}' ,[ResearchOfficeController::class, 'approveProposals']);
    Route::get('/download-proposal/{id}' ,[ResearchOfficeController::class, 'downloadProposal']);
    
    // researcher
    Route::post('/upload-document-researcher',[ResearcherController::class, 'uploadDocument']);
    Route::get('/get-document-researcher' ,[ResearcherController::class, 'getDocument']);
    Route::post('/update-document-researcher/{id}' ,[ResearcherController::class, 'updateDocument']);
    //upload proposal
    
    Route::post('/upload-proposal' ,[FrontPageControler::class, 'uploadProposal']);
    Route::get('/proposal-status' ,[FrontPageControler::class, 'proposalStatus']);

    

});


// no auth
Route::get('/all-departments' ,[DepartmentController::class, 'allDept']);
Route::get('/colleges' ,[CollegeController::class, 'index']);
Route::get('/admissionTypes' ,[AdmissionTypeController::class, 'index']);
Route::get('/years',[YearController::class,'index']);
Route::get('/studyLevels' ,[StudyLevelController::class, 'index']);

// front page
Route::get('/front-get-documents',[FrontPageControler::class,'getDocuments']);
Route::get('/get-front-announcements',[FrontPageControler::class,'getAnnouncements']);
Route::get('/latest-documents',[FrontPageControler::class,'getLatestDocuments']);
Route::get('/get-single-document/{id}',[FrontPageControler::class,'showDocument']);
Route::get('/download-documnet/{id}',[AllBackendController::class,'downloadDocument']);
Route::get('/download-comment/{id}',[AllBackendController::class,'downloadComment']);
//dashboard
Route::get('/all-user',[AllBackendController::class,'allUser']);
Route::get('/all-active-user',[AllBackendController::class,'allActiveUser']);
Route::get('/all-deactive-user',[AllBackendController::class,'allDeactiveUser']);
Route::get('/all-document',[AllBackendController::class,'allDocument']);
Route::get('/all-programs',[AllBackendController::class,'allPrograms']);



