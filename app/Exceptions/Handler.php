<?php

namespace App\Exceptions;

use App\Events\Admin\Error\Created;
use App\Models\Errors\Error;
use App\Models\Errors\PhpError;
use Cache;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

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
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception $exception
	 *
	 * @return void
	 */
	public function report(Exception $exception): void {

		if ($this->shouldReport($exception)) {
			try {
				$this->LogException($exception);
			} catch (\Exception $exception1) {
				dump($exception1);
			}
		}
		parent::report($exception);
	}

	/**
	 * @param Exception $exception
	 */
	protected function LogException(Exception $exception): void {
		$request = request();
		$error = new Error;
		$phpError = new PhpError;
		if ($request->user()) {
			$error->user_id = $request->user()->id;
		}
		$error->page = $request->fullUrl();
		$phpError->message = $exception->getMessage();
		$phpError->exception = json_encode([
			'class' => get_class($exception),
			'message' => $exception->getMessage(),
			'code' => $exception->getCode(),
			'file' => $exception->getFile(),
			'line' => $exception->getLine(),
			'trace' => $exception->getTrace(),
		]);
		$phpError->request = json_encode([
			'method' => $request->method(),
			'input' => $request->all(),
			'server' => $request->server(),
			'headers' => $request->header(),
			'cookies' => $request->cookie(),
			'session' => $request->hasSession() ? $request->session()->all() : '',
			'locale' => $request->getLocale(),

		]);
		$phpError->save();
		$phpError->error()->save($error);
		Cache::tags('PHPError')->flush();
		event(new Created($error));
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Exception $exception
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception) {
		return parent::render($request, $exception);
	}
}
