<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
         //return parent::render($request, $exception);


        // if(!empty($exception->getMessage())){
            
        //   $message = "Message : ".$exception->getMessage();
        //   \App\System::CustomLogWritter("errorlog","error_log",$message);
        // }

        // return \Redirect::to('/error/request');
 



        if ($this->isHttpException($exception)) {

           $message = "Message : ".$exception->getMessage();
           \App\System::CustomLogWritter("errorlog","error_log",$message);

            switch ($exception->getStatusCode()) {
                case '404':
                    return redirect('/error/request');
                    break;
                case '500':
                    return redirect('/errors/page');
                    break;
                case '403':
                    return redirect('/errors/page/503');
                    break;
                default:
                    return $this->renderHttpException($exception);
                    break;
            }

        // }elseif ($exception instanceof \Illuminate\Session\TokenMismatchException){            
        //     return redirect('/')->withErrors(['token_error' => 'Sorry, your session seems to have expired. Please try again.']);

        } else {
            return parent::render($request, $exception);
        }









    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(url('/login'));
    }
}
