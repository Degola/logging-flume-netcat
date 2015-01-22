<?php

namespace slc\logging\flume;

class Netcat_Connection_Exception extends Netcat_Exception {
	const EXCEPTION_BASE = 50002000;
	const WRITE_FAILED = 1;
}

?>