<?php
/**
 * @package Khooz\Socket
 * @author Mustafa Talaeezadeh Khouzani <brother.t@live.com>
 */

namespace Khooz\Socket;

use Khooz\Socket\Exceptions\InvalidAddressException;
use Khooz\Socket\Exceptions\InvalidPortException;
use Khooz\Socket\Utilities\INETADRR;

/**
 * InetAddr class
 *
 * An object oriented wrapper for an IP address/port pair
 */
class InetAddr
{
	/**
	 * IP address
	 *
	 * @var string
	 */
	protected $address;

	/**
	 * IP version
	 *
	 * @var integer
	 */
	protected $version;

	/**
	 * Port
	 *
	 * @var integer
	 */
	protected $port;

	/**
	 * Dynamic attributes
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Setter for $address
	 *
	 * @param string $value A valid IP address
	 *
	 * @throws Khooz\Socket\Exceptions\InvalidAddressException
	 * @return string
	 */
	protected function setAddress (string & $value) : string
	{
		$value	= gethostbyname($value);
		if (!preg_match(INETADRR::IPV4ADDR, $value))
		{
			if (!preg_match(INETADRR::IPV6ADDR, $value))
			{
				throw new InvalidAddressException();
			}
			$this->version	= INETADRR::IPv6;
		}
		else
		{
			$this->version	= INETADRR::IPv4;
		}
		$this->address	= $value;

		return $this->address;
	}

	/**
	 * Setter for $port
	 *
	 * @param integer $value A valid IP address
	 *
	 * @throws Khooz\Socket\Exceptions\InvalidPortException
	 * @return integer
	 */
	protected function setPort (& $value) : int
	{
		if ($value < 1 || $value > 65535)
		{
			throw new InvalidPortException();
		}

		return $this->port	= $value;
	}

	/**
	 * Getter for $address
	 *
	 * @return string
	 */
	protected function getAddress () : string
	{
		return $this->address;
	}

	/**
	 * Getter for $port
	 *
	 * @return integer
	 */
	protected function getPort () : int
	{
		return $this->port;
	}

	/**
	 * Getter for $version
	 *
	 * @return integer
	 */
	protected function getVersion () : int
	{
		return $this->version;
	}

	// BEGIN: Magic Methods

	public function & __get ($name)
	{
		$method = 'get' . ucfirst($name);
		if (method_exists($this, $method))
		{
			$k	= $this->$method();

			return $k;
		}
		else if (array_key_exists($name, $this->attributes))
		{
			return $this->attributes[$name];
		}
		$trace	= debug_backtrace();
		trigger_error(
			"Undefined property via __get(): {$name} in {$trace[0]['file']} on line {$trace[0]['line']}",
			E_USER_NOTICE);
		$k	= null;

		return $k;
	}

	public function __set ($name, $value)
	{
		$method	= 'set' . ucfirst($name);
		if (method_exists($this, $method))
		{
			return $this->$method($value);
		}
		else
		{
			$this->attributes[$name]	= &$value;

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

	/**
	 * Constructor for InetAddr
	 *
	 * @param string $address A valid IP address
	 * @param integer $port A valid port
	 *
	 * @throws Khooz\Socket\Exceptions\InvalidAddressException
	 * @throws Khooz\Socket\Exceptions\InvalidPortException
	 */
	public function __construct(string $address, int $port)
	{
		$this->attributes	= [];
		$this->setAddress($address);
		$this->setPort($port);
	}

	// END: Magic Methods

}