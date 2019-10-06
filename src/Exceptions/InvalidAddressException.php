<?php

namespace Khooz\Socket\Exceptions;

use Exception;

class InvalidAddressException extends Exception
{
	public const CODE_MASK	= 0x00010000;
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
	{
		$message	= empty($$message) ? "Invalid address" : $message;
		$code		|= static::CODE_MASK;
		parent::__construct($message, $code, $previous);
	}
}