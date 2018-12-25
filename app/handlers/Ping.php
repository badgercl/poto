<?php

class Ping extends AbstractCommand {
	public function processCommand( $m, $telegram, $model, $extras ) {
		$telegram->sendMsg( "Pong!", $m['chat']['id'] );
	}	
}	