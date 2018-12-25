<?php

interface Command {
	public function accepts( $candidate );
	public function processCommand( $m, $telegram, $model, $extras );
}

interface CallbackQuery {
	public function processCallback( $m, $telegram, $model, $extras );
}

interface NewChatMembers {
	public function processNewChatMembers( $m, $telegram, $model, $extras );
}

interface LeftChatMember {
	public function processLeftChatMember( $m, $telegram, $model, $extras );
}

interface NewGroupCreated {
	public function processNewGroupCreated( $m, $telegram, $model, $extras );
}

interface UpgradedToSupergroup {
	public function processUpgradedToSupergroup( $m, $telegram, $model, $extras );
}