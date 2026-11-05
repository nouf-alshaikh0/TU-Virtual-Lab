<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '/', 'middleware' => ['guest']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/user-login', [AuthController::class, 'userLogin'])->name('user-login');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/forgot_password_link', [AuthController::class, 'passwordLink'])->name('forgot.password.link');
    Route::get('/reset_password/{token}', [AuthController::class, 'resetPassword'])->name('reset.password');
    Route::post('/reset_password_post', [AuthController::class, 'submitResetPassword'])->name('reset.password.post');
});

Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', [Controller::class, 'index'])->name('dashboard');
    Route::get('/practice-lab', [Controller::class, 'practiceLab'])->name('practice-lab');
    Route::get('/practice-lab/{lang}', [Controller::class, 'practiceLabLang']);
    Route::post('/run-practice', [Controller::class, 'runPractice'])->name('run-practice');
    Route::get('/section/{id}/{schedule?}', [Controller::class, 'section']);
    Route::get('/attendances/{id}', [Controller::class, 'attendances']);
    Route::get('/marks/{id}', [Controller::class, 'marks']);
    Route::get('/users', [Controller::class, 'getUsers']);
    Route::post('/import_users', [Controller::class, 'importUsers'])->name('import_users');
    Route::get('/sections', [Controller::class, 'getSections']);
    Route::post('/import_sections', [Controller::class, 'importSections'])->name('import_sections');
    Route::get('/students', [Controller::class, 'getStudents']);
    Route::post('/import_std_section', [Controller::class, 'importStdSection'])->name('import_std_section');
    Route::get('/schedules', [Controller::class, 'getSchedules']);
    Route::post('/import_schedules', [Controller::class, 'importSchedules'])->name('import_schedules');
    Route::get('/live_session/{id}', [Controller::class, 'liveSession']);
    Route::get('/schedule/{id}/{type?}', [Controller::class, 'schedule']);
    Route::get('/view-code/{id}', [Controller::class, 'viewCode']);
    Route::get('/new-code/{id}', [Controller::class, 'newCode']);
    Route::post('/submit-code', [Controller::class, 'submitCode'])->name('submit-code');
    Route::post('/run-code', [Controller::class, 'runCode'])->name('run-code');
    Route::get('/compile', [Controller::class, 'compile']);
    Route::get('/start-session/{id}', [Controller::class, 'startSession']);
    Route::post('/get-code', [Controller::class, 'getCode'])->name('get-code');
    Route::post('/result', [Controller::class, 'result'])->name('result');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/', [AuthController::class, 'index']);
Route::view('/meet', 'meet');

use App\Http\Controllers\ContactController;

Route::get('/contact_us', [ContactController::class, 'showContactForm'])->name('contact_us');
Route::post('/contact_us', [ContactController::class, 'submitContactForm'])->name('contact_us.submit');


use App\Http\Controllers\AboutController; // Make sure to import your AboutController

Route::get('/about_us', [AboutController::class, 'showAboutUs'])->name('about_us');
