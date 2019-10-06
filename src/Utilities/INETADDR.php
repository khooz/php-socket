<?php
/**
 * @package Khooz\Socket
 * @subpackage Khooz\Socket\Unitilies
 * @author Mustafa Talaeezadeh Khouzani <brother.t@live.com>
 */

namespace Khooz\Socket\Utilities;

/**
 * INETADDR class
 *
 * An enumerator for IP address types and formatting
 */
class INETADRR
{

	/**
	 * @var string IPv4 anycast address
	 */
	const ANYv4			= "0.0.0.0";

	/**
	 * @var string IPv4 broadcast address
	 */
	const BROADCASTv4	= "255.255.255.255";

	/**
	 * @var string IPv4 link-local address
	 */
	const LOCALv4		= "127.0.0.1";

	/**
	 * @var string IPv6 anycast address
	 */
	const ANYv6			= "0:0:0:0:0:0:0:0";

	/**
	 * @var string IPv6 broadcast address
	 */
	const BROADCASTv6	= "FF02::1";

	/**
	 * @var string IPv6 link-local address
	 */
	const LOCALv6		= "::1";

	/**
	 * @var integer IPv4 tag
	 */
	const IPv4			= AF_INET;

	/**
	 * @var integer IPv6 tag
	 */
	const IPv6			= AF_INET6;

	/**
	 * IPv4 segment regex
	 *
	 * @var string
	 */
	const IPV4SEG		= <<<"REGEX"
(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])
REGEX;

	/**
	 * IPv4 address regex
	 *
	 * @var string
	 */
	const IPV4ADDR		= <<<"REGEX"
/^((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])$/
REGEX;

	/**
	 * IPv6 segment regex
	 *
	 * @var string
	 */
	const IPV6SEG 		= <<<"REGEX"
[0-9a-fA-F]{1,4}
REGEX;

	/**
	 * IPv6 address regex
	 * Limited support for IPv6 since it has many forms
	 *
	 * @var string
	 */
	const IPV6ADDR		= <<<"REGEX"
/^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{1,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/
REGEX;

}