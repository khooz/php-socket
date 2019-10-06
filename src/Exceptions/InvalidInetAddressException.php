<?php

namespace Khooz\Socket\Exceptions;

use Exception;

class InvalidInetAddressException extends Exception
{
	public const CODE_MASK	= 0x00030000;
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
	{
		$message	= empty($$message) ? "Invalid internet address" : $message;
		$code		|= static::CODE_MASK;
		parent::__construct($message, $code, $previous);
	}
}