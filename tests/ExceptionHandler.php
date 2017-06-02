<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler as LaravelExceptionHandler;

trait ExceptionHandler
{
    protected $oldExceptionHandler;

    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(LaravelExceptionHandler::class);
        $this->app->instance(LaravelExceptionHandler::class, $this->mockHandler());

        return $this;
    }

    private function mockHandler()
    {
        return new class extends Handler
        {
            public function __construct()
            {
            }

            public function report(\Exception $e)
            {
            }

            public function render($request, \Exception $e)
            {
                throw $e;
            }
        };
    }

    protected function enableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}
