<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\PostControllercls;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/about', function () {
//     return view('about');
// });


// http verbs
// get, post, put/patch, delete, any, match 

// Route::get('/user', function () {
//     return 'User Access';
// });

// Route::post('/user', function (){
//     return 'User Created';
// });

// Route::match(['get', 'post'], '/user', function () {
//     return 'User Access';
// });

// Route::put('/user/{id}', function ($id) {
//     return "User with ID {$id} updated";
// });

// Route::patch('/user/{id}', function ($id) {
//     return "User with ID {$id} partially updated";
// });

// Route::delete('/user/{id}', function ($id) {
//     return "User with ID {$id} deleted";
// });

// Route::any('/anything', function() {
//     return 'Matched any HTTP method';
// });



// // Route parameter
// Route::get('/user/{id}', function ($id) {
//     return "User with ID {$id}";
// });


// // optional Route parameter
// Route::get('/user/{id?}', function ($id = null) {
//     return "User with ID {$id}";
// });


//multiple parameter
// Route::get('/user/{id}/{name}', function ($id, $name) {
//     return "User with ID {$id} and Name {$name}";
// });



// // named parameter
// Route::get('/user/{id}', function ($id) {
//     return "User id {$id}";
// })->name('user.show');

// // grouping
// Route::prefix('admin')->group(function (){
//     Route::get('/dashboard', function (){
//         return 'Admin Dashboard';
//     });

//     Route::get('/settings', function (){
//         return 'Admin Settings';
//     });

// });

// // middleware
// Route::middleware(['auth'])->group(function (){
//     Route::get('/profile', function (){
//         return 'User Profile';
//     });

//     Route::get('/settings', function (){
//         return 'User Settings';
//     });
// });

// // combine 
// Route::prefix('admin')->middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function (){
//         return 'Admin Dashboard';
//     });

//     Route::get('/settings', function (){
//         return 'Admin Settings';
//     });
// });


// // Constraint 
// Route::get('/user/{id}', function ($id){
//     return "User with ID {$id}";
// })->where('id', '[0-9]+');

// Route::get('/post/{slug}', function ($slug){
//     return "Post with Slug {$slug}";
// })->where('slug', '[a-zA-Z0-9-]+');

// fallback
Route::fallback(function () {
    return view('errors.404');
});

// Route::get('/posts', [PostControllercls::class, 'index'])->name('posts.index');
// Route::get('/posts/create', [PostControllercls::class, 'create'])->name('posts.create');
// Route::post('/posts', [PostControllercls::class, 'store'])->name('posts.store');
// Route::get('/posts/{$id}', [PostControllercls::class, 'show'])->name('posts.show');
// Route::get('/posts/{$id}/edit', [PostControllercls::class, 'edit'])->name('posts.edit');
// Route::put('/posts/{$id}', [PostControllercls::class, 'update'])->name('posts.update');
// Route::delete('/posts/{$id}', [PostControllercls::class, 'destory'])->name('posts.destory');

// alternative for above 7 routes
// Route::resource('/posts', PostControllercls::class);

// Route::resource('/posts', PostControllercls::class)->only(['index', 'show']);

// Route::resource('/posts', PostControllercls::class)->except(['destory', 'update']);


// // grouping postController routes
// Route::controller(PostControllercls::class)->group(function (){
//     Route::get('/posts', 'index')->name('posts.index');
//     Route::get('/posts/create', 'create')->name('posts.create');
//     Route::post('/posts', 'store')->name('posts.store');
//     Route::get('/posts/{id}', 'show')->name('posts.show');
//     Route::get('/posts/{id}/edit', 'edit')->name('posts.edit');
//     Route::put('/posts/{id}', 'update')->name('posts.update');
//     Route::delete('/posts/{id}', 'destroy')->name('posts.destroy');
// });



Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth'])->group(function (){
    Route::get('/chirps', [ChirpController::class, 'index'])->name('chirps.index');
    Route::post('/chirps', [ChirpController::class, 'store'])->name('chirps.store');

    Route::get('/chirps/{id}/edit', [ChirpController::class, 'edit'])->name('chirps.edit');
    Route::put('/chirps/{id}', [ChirpController::class, 'update'])->name('chirps.update');

    Route::delete('/chirps/{id}', [ChirpController::class, 'destroy'])->name('chirps.destroy');
});






