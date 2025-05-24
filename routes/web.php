<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Todo
    Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');
    Route::get('/todo/create', [TodoController::class, 'create'])->name('todo.create');
    Route::patch('/todo/{todo}/complete', [TodoController::class, 'complete'])->name('todo.complete');
    Route::patch('/todo/{todo}/uncomplete', [TodoController::class, 'uncomplete'])->name('todo.uncomplete');
    Route::resource('todo', TodoController::class)->except(['show']);
    Route::get('/todo/{todo}/edit', [TodoController::class, 'edit'])->name('todo.edit');
    Route::patch('/todo/{todo}', [TodoController::class, 'update'])->name('todo.update');
    Route::delete('/todo/{todo}', [TodoController::class, 'destroy'])->name('todo.destroy');
    Route::delete('/todo', [TodoController::class, 'destroyCompleted'])->name('todo.deleteallcompleted');

    // Category
    Route::resource('category', CategoryController::class);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::delete('/user/{user}', [UserController:: class, 'destroy'])->name('user.destroy');
    Route::resource('user', UserController::class)->except(['show']);
    Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
    Route::patch('/user/{user}/removedadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');
});


// Route::get('/todo', [TodoController::class, 'view'])->name('todo.view');

// Route::get('/user', [UserController::class, 'index'])->name('user.index');

require __DIR__.'/auth.php';
