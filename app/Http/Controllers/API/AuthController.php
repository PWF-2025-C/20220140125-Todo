<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Login user dengan email dan password.
     */
    public function login(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // Cek apakah email dan password diisi
            if (empty($data['email']) || empty($data['password'])) {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Email dan password harus diisi',
                ], 400);
            }

            // Mencoba melakukan login menggunakan JWT
            if (!$token = Auth::guard('api')->attempt($data)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Email atau password salah',
                ], 401);
            }

            // Mengambil data user yang sedang login
            $user = Auth::guard('api')->user();

            // Mengembalikan response dengan data user dan token
            return response()->json([
                'status_code' => 200,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_admin' => $user->is_admin,
                        'token' => $token,
                    ]
                ]
            ], 200);
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan kesalahan
            return response()->json([
                'status_code' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // #[Response(
    //     status: 200,
    //     content: [
    //         'status_code' => 200,
    //         'message' => 'Logout berhasil. Token telah dihapus.'
    //     ]
    // )]
    // #[Response(
    //     status: 500,
    //     content: [
    //         'status_code' => 500,
    //         'message' => 'Gagal logout, terjadi kesalahan.'
    //     ]
    // )]
    /**
     * Logout user yang sedang login.
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status_code' => 200,
                'message' => 'Logout Berhasil. Token telah dihapus.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Gagal logout, terjadi kesalahan.',
            ], 500);
        }
    }
}