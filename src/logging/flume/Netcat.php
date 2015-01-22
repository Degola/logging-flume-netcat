<?php

namespace slc\logging\flume;

/**
 *
 * User: Sebastian Lagemann <sl@honeytracks.com>
 * Date: 13.11.2012
 * Time: 15:40
 *
 * Small flume netcat driver class which allows writing to a flume netcat source
 */

class Netcat {
	const DEBUG = false;
	protected $config = null;
	protected $Connection = null;

	public function __construct(array $config) {
		$this->config = (object) $config;

		if(!isset($this->config->Host))
			throw new Netcat_Exception('CONFIGURATION_MISMATCH', array('Configuration' => $this->config));

		if (!isset($this->config->Port)) {
			$this->config->Port = 6000;
		}
		if(!isset($this->config->Connections)) {
			$this->config->Connections = 1;
		}
	}

	/**
	 * Fetches the Beanstalk connection if it exists and creates it otherwise.
	 *
	 * @return Netcat_Connection
	 */

	protected function getConnection() {
		if (is_null($this->Connection)) {
			$this->Connection = new Netcat_Connection(
				$this->config->Host,
				$this->config->Port,
				$this->config->Connections
			);
		}
		return $this->Connection;
	}

	public function addLogEntry($data, $timestamp = null) {
		if(is_null($timestamp))
			$timestamp = time();

		$handlers = $this->getConnection();
		return $this->getConnection()->addLogEntry($timestamp.' '.$data, $handlers);
	}

	/**
	 * disconnects get connection
	 *
	 * @param $forceHarshDisconnect tells the disconnect method to not wait for received data and to kill the connection
	 * immediately otherwise it could happen that we hang in an endless loop
	 * @return bool
	 */
	public function disconnect($forceHarshDisconnect = false) {
		$conn = $this->getConnection();
		$remainingConnections = 1;
		if($forceHarshDisconnect === false) {
			do {
				$package = $conn->receive();
				foreach($package AS $packet) {
					$remainingConnections = $conn->disconnect($packet->getHandler());
				}
			} while($remainingConnections > 0);
		} else {
			$conn->disconnect();
		}
		$this->shutdown = true;
	}
}

?>