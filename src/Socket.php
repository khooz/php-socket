<?php
/**
 * @package Khooz\Socket
 * @author Mustafa Talaeezadeh Khouzani <brother.t@live.com>
 */

namespace Khooz\Socket;

use Khooz\Socket\Exceptions\InvalidInetAddressException;
use Khooz\Socket\Exceptions\InvalidProtocolException;
use Khooz\Socket\Exceptions\InvalidSocketException;
use Khooz\Socket\Utilities\INETADRR;

/**
 * Socket class
 *
 * An object wrapper for socket
 */
class Socket
{

	/**
	 * Binding address
	 *
	 * @var InetAddr
	 */
	protected $address;

	/**
	 * Resource underlying the socket
	 *
	 * @var resource
	 */
	protected $resource;

	/**
	 * Protocol
	 *
	 * @var integer
	 */
	protected $protocol;

	/**
	 * Socket type
	 *
	 * @var integer
	 */
	protected $sockType;

	/**
	 * Remote connection address
	 *
	 * @var InetAddr
	 */
	protected $remote;

	/**
	 * Dynamic attributes
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Mapping protocols to socket types
	 *
	 * @var array
	 */
	private static $PROTOCOL_MAP_SOL2SOCK	= [
		SOL_TCP		=> SOCK_STREAM,
		SOL_UDP		=> SOCK_DGRAM,
		SOL_ICMP	=> SOCK_RAW,
	];

	/**
	 * Mapping socket types to protocols
	 *
	 * @var array
	 */
	private static $PROTOCOL_MAP_SOCK2SOL	= [
		SOCK_STREAM	=> SOL_TCP,
		SOCK_DGRAM	=> SOL_UDP,
		SOCK_RAW	=> SOL_ICMP,
	];

	/**
	 * Setter for $address
	 *
	 * @param InetAddr
	 *
	 * @return InetAddr
	 */
	protected function setInetAddr(InetAddr & $value) : InetAddr
	{
		return $this->address	= $value;
	}

	/**
	 * Setter for $resource
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
	 * Setter for $protocol
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
	 * Setter for $sockType
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
	 * Getter for $address
	 *
	 * @return InetAddr
	 */
	protected function getInetAddr() : InetAddr
	{
		return $this->address;
	}

	/**
	 * Getter for $resource
	 *
	 * @return resource
	 */
	protected function & getResource()
	{
		return $this->resource;
	}

	/**
	 * Getter for $protocol
	 *
	 * @return integer
	 */
	protected function getProtocol() : int
	{
		return $this->protocol;
	}

	/**
	 * Getter for $sockType
	 *
	 * @return integer
	 */
	protected function getSockType() : int
	{
		return $this->sockType;
	}

	/**
	 * Creates a new socket and binds to the $address
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
		if (empty($this->address) || empty($this->address->version))
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

		if (($this->resource = @socket_create($this->address->version, $this->sockType, $this->protocol)) === false)
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
	 * Binding a socket to $address
	 *
	 * @throws InvalidSocketException
	 * @return Socket
	 */
	protected function bind() : Socket
	{
		if (@socket_bind($this->resource, $this->address->address, $this->address->port) === false)
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
	 * Starts listening to a socket
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
	 * Accepting new connection
	 *
	 * @param integer $blocking
	 * @param callable $callback
	 *
	 * @throws InvalidSocketException
	 * @return mixed
	 */
	public function accept(int $blocking = SOCKET_BLOCK, callable $callback)
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
			if ($error)
			{
				throw new InvalidSocketException(
					"socket_accept: ($error) " . socket_strerror($error)
					, $error
				);
			}
			else if ($blocking === SOCKET_NONBLOCK)
			{
				return;
			}
		}
		$sock = new Socket($this->address, $this->protocol);
		$sock->setResource($newsock);
		$retval = call_user_func_array($callback, [&$sock]);
		if (get_resource_type($sock->resource) === "Socket") {
			$sock->close();
		}
		return $retval;
	}

	/**
	 * Connecting to a remote address
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
		if (empty($this->address) || (empty($this->address->address) && empty($this->address->port)))
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
		if (@socket_connect($this->resource, $this->address->address, $this->address->port) === false) {
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
	 * Receiving data from stream buffer
	 *
	 * @param integer $length
	 * @param integer $flags
	 * @param string& $address
	 * @param integer& $port
	 *
	 * @return string
	 */
	public function receive(int $length = null, int $flags = 0, string & $address = null, int & $port = null) : ?string
	{
		if ($length === null)
		{
			$length = static::return_bytes(ini_get('post_max_size'));
		}
		if ($this->protocol === SOL_UDP)
		{
			socket_recvfrom($this->resource, $buf, $length, $flags, $address, $port);
			$this->remote = new InetAddr($address, $port);
		}
		else
		{
			socket_recv($this->resource, $buf, $length, $flags);
		}

		return $buf;
	}

	/**
	 * Sending data onto stream buffer
	 *
	 * @param string $buffer
	 * @param integer $flags
	 * @param string $address
	 * @param integer $port
	 * @return integer
	 */
	public function send(string $buffer = null, int $flags = 0, string $address = null, int $port = null) : int
	{
		if ($this->protocol === SOL_UDP)
		{
			if ($address === null)
			{
				$address = $this->remote->address ?? INETADRR::BROADCASTv4;
			}
			if ($port === null)
			{
				$port = $this->remote->port ?? 0;
			}
			$sent_bytes = socket_sendto($this->resource, $buffer, strlen($buffer), $flags, $address, $port);
		}
		else
		{
			$sent_bytes = socket_send($this->resource, $buffer, strlen($buffer), $flags);
		}

		return $sent_bytes;
	}

	/**
	 * Closing a socket
	 *
	 * @return void
	 */
	public function close() : void
	{
		socket_close($this->resource);
		return;
	}

	/**
	 * Converts human readable size to bytes
	 *
	 * @param string $val
	 * @return int
	 */
	private static function return_bytes (string $val) : int
    {
		if(empty($val))
		{
			return 0;
		}

		$val = trim($val);

        preg_match('/([0-9]+)\s*([a-z]+)/i', $val, $matches);

        $last = '';
        if(isset($matches[2])){
            $last = $matches[2];
        }

        if(isset($matches[1])){
            $val = (int) $matches[1];
        }

        switch (strtolower($last))
        {
            case 'g':
            case 'gb':
                $val *= 1024;
            case 'm':
            case 'mb':
                $val *= 1024;
            case 'k':
            case 'kb':
                $val *= 1024;
        }

        return (int) $val;
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