<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception) {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception) {
        if (true || $request->wantsJson()) {
            $response = [
                "errors" => "Sorry, something went wrong."
            ];
            if (config("app.debug")) {
                $response["exception"] = get_class($exception);
                $response["message"] = $exception->getMessage();
            }
            $status = 400;
            if ($this->isHttpException($exception)) {
                $status = $exception->getStatusCode();
            }
            return response()->json($response, $status);
        }
        return parent::render($request, $exception);
    }
}
