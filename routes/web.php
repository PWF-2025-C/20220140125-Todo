    <?php

    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\TodoController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\CategoryController;
    use Illuminate\Support\Facades\Route;

    // Route utama (halaman welcome)
    Route::get('/', function () {
        return view('welcome');
    });

    // Route untuk dashboard, hanya bisa diakses oleh pengguna yang sudah login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');


    require __DIR__ . '/auth.php';

    // Route yang membutuhkan autentikasi
    Route::middleware('auth', 'verified')->group(function () {
        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // TODO Routes
        Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');
        Route::get('/todo/create', [TodoController::class, 'create'])->name('todo.create');
        Route::post('/todo', [TodoController::class, 'store'])->name('todo.store');
        Route::get('/todo/{todo}/edit', [TodoController::class, 'edit'])->name('todo.edit');

        Route::patch('/todo/{todo}/complete', [TodoController::class, 'complete'])->name('todo.complete');
        Route::patch('/todo/{todo}/incomplete', [TodoController::class, 'uncomplete'])->name('todo.uncomplete');

        Route::patch('/todo/{todo}', [TodoController::class, 'update'])->name('todo.update');

        Route::delete('/todo/{todo}', [TodoController::class, 'destroy'])->name('todo.destroy');
        Route::delete('/todo', [TodoController::class, 'destroyCompleted'])->name('todo.deleteallcompleted');

        
        Route::resource('category', CategoryController::class)->except(['show']);
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        // User Routes
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
        Route::patch('/user/{user}/removeadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');

        Route::resource('user', UserController::class)->except(['show']);
    });
