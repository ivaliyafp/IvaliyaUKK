<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ImageController as AdminImageController;
use App\Models\Image;
use App\Models\User;

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

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $images = Image::where('user_id', auth()->id())->latest()->get();
        return view('dashboard', compact('images'));
    })->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/home', [HomeController::class, 'indexadmin'])->name('home.indexadmin');
    Route::get('/home/create', [HomeController::class, 'create'])->name('home.create');
    Route::post('/home', [HomeController::class, 'store'])->name('home.store');
    Route::delete('/home/{id}', [HomeController::class, 'destroy'])->name('home.destroy');
    Route::post('/images/{image}/like', [LikeController::class, 'toggle'])->name('images.like');
    Route::post('/comment/{image}', [CommentController::class, 'store'])->name('comment.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // CRUD Gambar
    Route::resource('images', AdminImageController::class);
    Route::patch('/images/{image}/toggle', [AdminImageController::class, 'toggleActive'])->name('images.toggle');

    // Pending approval
    
    Route::get('/pending-images', [AdminController::class, 'pendingImages'])->name('pending.images');
    Route::post('/approve-image/{id}', [AdminController::class, 'approve'])->name('approve.image');
});

Route::post('/comment/{comment}/like', [CommentLikeController::class, 'toggle'])->name('comment.like');
Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');

route::get('/kontak', function () {
    $users = User::latest()->get(); 
    return view('kontak', compact('users'));
})->middleware('auth')->name('kontak');

require __DIR__.'/auth.php';
