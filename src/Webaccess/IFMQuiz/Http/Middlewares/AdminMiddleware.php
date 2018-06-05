<?php

namespace Webaccess\IFMQuiz\Http\Middlewares;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     */
    public function __construct()
    {
        $this->auth = Auth::guard('administrators');
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->auth->user())
        {
            if ($request->ajax())
            {
                return response('Forbbiden.', 403);
            }
            else
            {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}