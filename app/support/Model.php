<?php

foreach(glob(__DIR__ . "/../model/*.php") as $file) {
	require_once $file;
}

class Model {
	public $user;
	public $userLog;
	public $callbackLog;

	function __construct($db) {
		$this->user = new User($db);
		$this->userLog = new UserLog($db);
		$this->callbackLog = new CallbackLog($db);
	}
}
