<?php

namespace App\Exceptions;

// [MODIFIKASI] Import trait dan class exception yang kita butuhkan
use App\Http\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    // [MODIFIKASI] Gunakan trait yang sudah kita buat
    use ApiResponse;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // [MODIFIKASI] Di sini kita menambahkan logika untuk menangani error API secara spesifik
        $this->renderable(function (Throwable $e, $request) {
            // Kita hanya menjalankan logika ini jika request ditujukan ke rute API
            if ($request->is('api/*')) {

                // Jika rute tidak ditemukan (404)
                if ($e instanceof NotFoundHttpException) {
                    return $this->errorResponse('Resource tidak ditemukan.', 404);
                }

                // Jika pengguna tidak terautentikasi (401)
                if ($e instanceof AuthenticationException) {
                    return $this->errorResponse('Unauthenticated. Silakan login terlebih dahulu.', 401);
                }

                // Jika terjadi error validasi dari FormRequest (422)
                if ($e instanceof ValidationException) {
                    $errors = $e->validator->errors()->all();
                    return $this->errorResponse(implode(' ', $errors), 422);
                }

                // Default error untuk semua jenis kesalahan lain di API (500)
                // Sebaiknya jangan tampilkan pesan error asli di produksi untuk keamanan
                $errorMessage = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada server.';
                return $this->errorResponse($errorMessage, 500);
            }
        });
    }
}