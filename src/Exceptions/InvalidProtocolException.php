<?php

namespace Khooz\Socket\Exceptions;

use Exception;

class InvalidProtocolException extends Exception
{
	public const CODE_MASK	= 0x00080000;
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
	{
		$message	= empty($$message) ? "Invalid protocol" : $message;
		$code		|= static::CODE_MASK;
		parent::__construct($message, $code, $previous);
	}
}