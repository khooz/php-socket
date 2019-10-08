<?php


/**
 * IP Versions
 */
defined("AF_INET")			?: define("AF_INET",			2);		/* IPv4 */
defined("AF_INET6")			?: define("AF_INET6",			10);	/* IPv6 */

/**
 * Protocols
 */
defined("SOL_TCP")			?: define("SOL_TCP",			6);		/* TCP protocol */
defined("SOL_UDP")			?: define("SOL_UDP",			17);	/* UDP protocol */
defined("SOL_ICMP")			?: define("SOL_ICMP",			1);		/* ICMP protocol */

/**
 * Socket Types
 */
defined("SOCK_STREAM")		?: define("SOCK_STREAM",		1);		/* Stream IO */
defined("SOCK_DGRAM")		?: define("SOCK_DGRAM",			2);		/* Datagram IO */
defined("SOCK_RAW")			?: define("SOCK_RAW",			3);		/* Raw IO */

/**
 * Blocking
 */
defined("STREAM_BLOCK")		?: define("STREAM_BLOCK",		1);		/* Stream blocking accept */
defined("SOCKET_BLOCK")		?: define("SOCKET_BLOCK",		1);		/* Socket Blocking accept */
defined("STREAM_NONBLOCK")	?: define("STREAM_NONBLOCK",	0);		/* Stream non-blocking accept */
defined("SOCKET_NONBLOCK")	?: define("SOCKET_NONBLOCK",	0);		/* Socket non-blocking accept */


/**
 * C style Error codes
 */
defined("ENOTSOCK")			?: define("ENOTSOCK",			88);	/* Socket operation on non-socket */
defined("EDESTADDRREQ")		?: define("EDESTADDRREQ",		89);	/* Destination address required */
defined("EMSGSIZE")			?: define("EMSGSIZE",			90);	/* Message too long */
defined("EPROTOTYPE")		?: define("EPROTOTYPE",			91);	/* Protocol wrong type for socket */
defined("ENOPROTOOPT")		?: define("ENOPROTOOPT",		92);	/* Protocol not available */
defined("EPROTONOSUPPORT")	?: define("EPROTONOSUPPORT",	93);	/* Protocol not supported */
defined("ESOCKTNOSUPPORT")	?: define("ESOCKTNOSUPPORT",	94);	/* Socket type not supported */
defined("EOPNOTSUPP")		?: define("EOPNOTSUPP",			95);	/* Operation not supported on transport endpoint */
defined("EPFNOSUPPORT")		?: define("EPFNOSUPPORT",		96);	/* Protocol family not supported */
defined("EAFNOSUPPORT")		?: define("EAFNOSUPPORT",		97);	/* Address family not supported by protocol */
defined("EADDRINUSE")		?: define("EADDRINUSE",			98);	/* Address already in use */
defined("EADDRNOTAVAIL")	?: define("EADDRNOTAVAIL",		99);	/* Cannot assign requested address */
defined("ENETDOWN")			?: define("ENETDOWN",			100);	/* Network is down */
defined("ENETUNREACH")		?: define("ENETUNREACH",		101);	/* Network is unreachable */
defined("ENETRESET")		?: define("ENETRESET",			102);	/* Network dropped connection because of reset */
defined("ECONNABORTED")		?: define("ECONNABORTED",		103);	/* Software caused connection abort */
defined("ECONNRESET")		?: define("ECONNRESET",			104);	/* Connection reset by peer */
defined("ENOBUFS")			?: define("ENOBUFS",			105);	/* No buffer space available */
defined("EISCONN")			?: define("EISCONN",			106);	/* Transport endpoint is already connected */
defined("ENOTCONN")			?: define("ENOTCONN",			107);	/* Transport endpoint is not connected */
defined("ESHUTDOWN")		?: define("ESHUTDOWN",			108);	/* Cannot send after transport endpoint shutdown */
defined("ETOOMANYREFS")		?: define("ETOOMANYREFS",		109);	/* Too many references: cannot splice */
defined("ETIMEDOUT")		?: define("ETIMEDOUT",			110);	/* Connection timed out */
defined("ECONNREFUSED")		?: define("ECONNREFUSED",		111);	/* Connection refused */
defined("EHOSTDOWN")		?: define("EHOSTDOWN",			112);	/* Host is down */
defined("EHOSTUNREACH")		?: define("EHOSTUNREACH",		113);	/* No route to host */
defined("EALREADY")			?: define("EALREADY",			114);	/* Operation already in progress */
defined("EINPROGRESS")		?: define("EINPROGRESS",		115);	/* Operation now in progress */
defined("EREMOTEIO")		?: define("EREMOTEIO",			121);	/* Remote I/O error */
defined("ECANCELED")		?: define("ECANCELED",			125);	/* Operation Canceled */