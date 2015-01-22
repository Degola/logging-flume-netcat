<?php

namespace slc\logging\flume;

/**
 * Packets which were received from the receive method.
 */
class Netcat_Packet {
	protected $connection;
	protected $handler;
	protected $header;
	protected $type;
	protected $data;
	protected $expectedType = null;
	public function __construct(Netcat_Connection $connection, $handler, $header, $expectedType = null) {
		$this->connection = $connection;
		$this->handler = $handler;
		$this->header = trim($header);
		$this->expectedType = $expectedType;
		$this->parse();
	}

	/**
	 * parses the response, currently only ok implemented and supported
	 */
	protected function parse() {
		list($handlerId) = array_keys($this->handler);
		$handler = $this->handler[$handlerId];
		switch($this->header) {
			case 'OK':
				$this->type = 'ok';
				break;
			default:
				if(Netcat_Connection::DEBUG)
					echo "unknown result: ".print_r($this->header, true)."\n";
				$this->data = $this->header;
		}
	}

	/**
	 * Fetches the packet's data.
	 *
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * Fetches the packet's type.
	 *
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Fetches the packet's handler.
	 *
	 * @return mixed
	 */
	public function getHandler() {
		return $this->handler;
	}
}

?>