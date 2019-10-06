<?php
/**
 * @package Khooz\Socket
 * @author Mustafa Talaeezadeh Khouzani <brother.t@live.com>
 */

namespace Khooz\Socket;

use Khooz\Socket\Exceptions\InvalidInetAddressException;
use Khooz\Socket\Exceptions\InvalidProtocolException;
use Khooz\Socket\Exceptions\InvalidSocketException;

/**
 * Socket class
 *
 * An object wrapper for socket
 */
class Socket
{

	/**
	 * Undocumented variable
	 *
	 * @var InetAddr
	 */
	protected $inetAddr;

	/**
	 * Undocumented variable
	 *
	 * @var resource
	 */
	protected $resource;

	/**
	 * Undocumented variable
	 *
	 * @var integer
	 */
	protected $protocol;

	/**
	 * Undocumented variable
	 *
	 * @var integer
	 */
	protected $sockType;

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	private static $PROTOCOL_MAP_SOL2SOCK	= [
		SOL_TCP		=> SOCK_STREAM,
		SOL_UDP		=> SOCK_DGRAM,
		SOL_ICMP	=> SOCK_RAW,
	];

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	private static $PROTOCOL_MAP_SOCK2SOL	= [
		SOCK_STREAM	=> SOL_TCP,
		SOCK_DGRAM	=> SOL_UDP,
		SOCK_RAW	=> SOL_ICMP,
	];

	/**
	 *
	 *
	 * @param InetAddr
	 *
	 * @return InetAddr
	 */
	protected function setInetAddr(InetAddr & $value) : InetAddr
	{
		return $this->inetAddr	= $value;
	}

	/**
	 *
	 *
	 * @param resource
	 *
	 * @throws InvalidSocketException
	 * @return resource
	 */
	protected function setResource(& $value)
	{
		if (get_resource_type($value) !== "Socket")
		{
			throw new InvalidSocketException();
		}
		return $this->resource	= $value;
	}

	/**
	 *
	 *
	 * @param integer
	 *
	 * @throws InvalidProtocolException
	 * @return integer
	 */
	protected function setProtocol(int & $value) : int
	{
		if (!in_array($value, static::$PROTOCOL_MAP_SOCK2SOL))
		{
			throw new InvalidProtocolException();
		}
		$this->sockType			= static::$PROTOCOL_MAP_SOL2SOCK[$value];

		return $this->protocol	= $value;
	}

	/**
	 *
	 *
	 * @param integer
	 *
	 * @return integer
	 */
	protected function setSockType(int & $value) : int
	{
		if (!in_array($value, static::$PROTOCOL_MAP_SOL2SOCK))
		{
			throw new InvalidSocketException();
		}
		$this->protocol			= static::$PROTOCOL_MAP_SOCK2SOL[$value];

		return $this->sockType	= $value;
	}

	/**
	 * Undocumented function
	 *
	 * @return InetAddr
	 */
	protected function getInetAddr() : InetAddr
	{
		return $this->inetAddr;
	}

	/**
	 *
	 *
	 * @return resource
	 */
	protected function & getResource()
	{
		return $this->resource;
	}

	/**
	 * Undocumented function
	 *
	 * @return integer
	 */
	protected function getProtocol() : int
	{
		return $this->protocol;
	}

	/**
	 * Undocumented function
	 *
	 * @return integer
	 */
	protected function getSockType() : int
	{
		return $this->sockType;
	}

	/**
	 * Undocumented function
	 *
	 * @param InetAddr $address
	 * @param integer $protocol
	 *
	 * @throws InvalidInetAddressException
	 * @throws InvalidProtocolException
	 * @throws InvalidSocketException
	 * @return Socket
	 */
	public function create(InetAddr $address = null, int $protocol = null) : Socket
	{
		if (!empty($address))
		{
			$this->setInetAddr($address);
		}
		if (empty($this->inetAddr) || empty($this->inetAddr->version))
		{
			throw new InvalidInetAddressException();
		}

		if (!empty($protocol))
		{
			$this->setProtocol($protocol);
		}
		if (empty($this->protocol))
		{
			throw new InvalidProtocolException();
		}

		if (($this->resource = @socket_create($this->inetAddr->version, $this->sockType, $this->protocol)) === false)
		{
			$this->resource	= null;
			$error			= socket_last_error();
			socket_clear_error();
			throw new InvalidSocketException(
				"socket_create: ($error) " . socket_strerror($error)
				, $error
			);
		}

		return $this->bind();
	}


	/**
	 * Undocumented function
	 *
	 * @throws InvalidSocketException
	 * @return Socket
	 */
	protected function bind() : Socket
	{
		if (@socket_bind($this->resource, $this->inetAddr->address, $this->inetAddr->port) === false)
		{
			$error	= socket_last_error($this->resource);
			socket_clear_error($this->resource);
			throw new InvalidSocketException(
				"socket_bind: ($error) " . socket_strerror($error)
				, $error
			);
		}
		return $this;
	}

	/**
	 * Undocumented function
	 *
	 * @throws InvalidSocketException
	 * @return Socket
	 */
	public function listen() : Socket
	{
		if (@socket_listen($this->resource, 5) === false) {
			$error	= socket_last_error($this->resource);
			socket_clear_error($this->resource);
			throw new InvalidSocketException(
				"socket_listen: ($error) " . socket_strerror($error)
				, $error
			);
		}
		return $this;
	}

	/**
	 * Undocumented function
	 *
	 * @param integer $blocking
	 * @param callable $callback
	 *
	 * @throws InvalidSocketException
	 * @return mixed
	 */
	public function accept(int $blocking = SOCKET_BLOCK, callable $callback) : mixed
	{
		switch ($blocking)
		{
			case SOCKET_BLOCK: {
				socket_set_block($this->resource);
				break;
			}
			case SOCKET_NONBLOCK: {
				socket_set_nonblock($this->resource);
				break;
			}
		}
		if (($newsock = @socket_accept($this->resource)) === false) {
			$error	= socket_last_error($this->resource);
			socket_clear_error($this->resource);
			throw new InvalidSocketException(
				"socket_accept: ($error) " . socket_strerror($error)
				, $error
			);
		}
		$sock = new Socket($this->inetAddr, $this->protocol);
		$sock->setResource($newsock);
		$retval = call_user_func_array($callback, [&$sock]);
		if (get_resource_type($sock->resource) === "Socket") {
			$sock->close();
		}
		return $retval;
	}

	/**
	 * Undocumented function
	 *
	 * @param InetAddr $address
	 * @param integer $protocol
	 *
	 * @throws InvalidInetAddressException
	 * @throws InvalidProtocolException
	 * @throws InvalidSocketException
	 * @return Socket
	 */
	public function connect(InetAddr $address = null, int $protocol = null) : Socket
	{
		if (!empty($address))
		{
			$this->setInetAddr($address);
		}
		if (empty($this->inetAddr) || (empty($this->inetAddr->address) && empty($this->inetAddr->port)))
		{
			throw new InvalidInetAddressException();
		}
		if (!empty($protocol))
		{
			$this->setProtocol($protocol);
		}
		if (empty($this->protocol))
		{
			throw new InvalidProtocolException();
		}
		if (@socket_connect($this->resource, $this->inetAddr->address, $this->inetAddr->port) === false) {
			$error	= socket_last_error($this->resource);
			socket_clear_error($this->resource);
			throw new InvalidSocketException(
				"socket_accept: ($error) " . socket_strerror($error)
				, $error
			);
		}
		return $this;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function close() : void
	{
		socket_close($this->resource);
		return;
	}

	// BEGIN: Magic Methods

	public function & __get($name)
	{
		$method = 'get' . ucfirst($name);
		if (method_exists($this, $method))
		{
			$k = $this->$method();

			return $k;
		}
		else if (array_key_exists($name, $this->attributes))
		{
			return $this->attributes[$name];
		}
		$trace = debug_backtrace();
		trigger_error(
			"Undefined property via __get(): {$name} in {$trace[0]['file']} on line {$trace[0]['line']}",
			E_USER_NOTICE);
		$k = null;

		return $k;
	}

	public function __set($name, $value)
	{
		$method = 'set' . ucfirst($name);
		if (method_exists($this, $method))
			return $this->$method($value);
		else {
			$this->attributes[$name] = &$value;
			return $this->attributes[$name];
		}
	}

	public function __isset($name)
	{
		if (in_array($name, array_merge(array_keys(get_object_vars($this)), array_keys($this->attributes))))
		{
			return true;
		}
		return false;
	}

	public function __construct($address = null, int $protocol = SOL_TCP)
	{
		$this->attributes	= [];
		$address			= $address ?? 'localhost:65535';
		$this->setInetAddr($address);
		$this->setProtocol($protocol);
	}

	// END: Magic Methods

}