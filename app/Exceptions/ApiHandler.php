<?php

namespace App\Exceptions;

use App\Http\Responses\ResponsesInterface;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiHandler extends ExceptionHandler
{

    /**
     * @var ResponsesInterface
     */
    private $apiResponder;


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
     * ApiHandler constructor.
     * @param Container $container
     * @param ResponsesInterface $apiResponder
     */
    public function __construct(Container $container, ResponsesInterface $apiResponder)
    {
        parent::__construct($container);
        $this->apiResponder = $apiResponder;
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return $this->apiResponder->respondNotFound();
        }
        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param \Illuminate\Validation\ValidationException $e
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->response ?? $e->validator->errors()->getMessages();

        return $this->apiResponder->respondWithValidationError($errors);
    }
}
