<?php

namespace slc\logging\flume;

class Netcat_Packet_Exception extends Netcat_Exception {
	const EXCEPTION_BASE = 50003000;
	const BAD_FORMAT = 1;
}

?>