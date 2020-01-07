<?php

namespace App\Http\Middleware;

use App\Feedback;
use App\Http\Responses\ResponsesInterface;
use Closure;

class CheckCompanyToken
{
    /**
     * @var ResponsesInterface
     */
    private $apiResponder;

    /**
     * CheckCompanyToken constructor.
     * @param ResponsesInterface $apiResponder
     */
    public function __construct(ResponsesInterface $apiResponder)
    {
        $this->apiResponder = $apiResponder;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Company-Token');

        if ($request->hasHeader('Company-Token') && Feedback::isTokenExist($token)) {
            return $next($request);
        }

        return $this->apiResponder->setStatusCode(403)->respondWithError('Invalid Token !!');
    }
}
