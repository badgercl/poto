<?php

abstract class AbstractCommand implements Command {
	protected $command;

	function __construct($command) {
        $this->command = $command;
    }

    function accepts( $candidate ) {
    	return $this->command == $candidate;
    }
}