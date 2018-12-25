<?php

foreach(glob(__DIR__ . "/../lib/*.php") as $file) {
	require_once $file;
}
require_once(__DIR__. "/HandlerInterfaces.php");
require_once(__DIR__. "/AbstractModel.php");
require_once(__DIR__. "/AbstractCommand.php");
foreach(glob(__DIR__ . "/../handlers/*.php") as $file) {
	require_once $file;
}
foreach(glob(__DIR__ . "/../support/*.php") as $file) {
	require_once $file;
}


abstract class EventType {
	const Message = "processMessage";
	const Command = "processCommand";
	const Location = "processLocation";
	const CallbackQuery = "processCallback";
	const InlineQuery = "processInlineQuery";
	const NewChatMembers = "processNewChatMembers";
	const LeftChatMember = "processLeftChatMember";
	const NewGroupCreated = "processNewGroupCreated";
	const UpgradedToSupergroup = "processUpgradedToSupergroup";
}

class PoTo {
	private $handlers;
	private $telegram;
	private $model;
	private $extras;

	function __construct($telegram, $model, $extras) {
		$this->handlers = [];

		$this->telegram = $telegram;
		$this->model = $model;
		$this->extras = $extras;
	}

	function process() {
		$m = $this->telegram->parseInput();
		list($m, $type) = $this->getType($m);

		// Logging and flood protection
		if( $type == EventType::Command ) {
			$last_log = $this->model->userLog->get( $m['from']['id'] );
			$date = strtotime( $last_log['created_at'] );
			$this->model->userLog->save( $m );
			echo time() - $date . "\n";
			if( (time() - $date) < 4 && $last_log['command'] == $m['bot_command']['cmd'] ) return;
			if( (time() - $date) < 1 ) return;
		}
		else if( $type == EventType::CallbackQuery ) {
			$this->model->callbackLog->save( $m );
		}

		if( $type == EventType::Command ) $this->start( $m );

		// Check creme restrictions
		if( isset($m['chat']) && $m['chat']['type'] != 'private' && $type == EventType::Command && $m['bot_command']['cmd'] != '/start' && $m['bot_command']['cmd'] != '/title' && !$this->extras['checker']->is_creme( $m )) {
			echo "isn't creme enough";
			error_log("isn't creme enough ". $m['chat']['id']);
			$this->telegram->sendMsg( "Este grupo no es suficientemente cremado. ¿Soy admin? ¿El titulo esta bien? Puedes definir el titulo con el comando /title + postfijo", $m['chat']['id'] );
			return; 
		}

		if( !isset($this->handlers[$type]) ) return;
		if( $type == EventType::Command ) {
			foreach( $this->handlers[EventType::Command] as $handler ) {
				if( !$handler->accepts( $m['bot_command']['cmd'] )) continue;
				$handler->processCommand( $m, $this->telegram, $this->model, $this->extras );
			}
		} else {
			foreach( $this->handlers[$type] as $handler ) {
				$handler->$type( $m, $this->telegram, $this->model, $this->extras );
			}
		}
	}

	function addHandler( $handler, $types ) {
		if ( !is_array($types) ) $types = [ $types ];
		foreach( $types as $type ) {
			$this->handlers[$type][] = $handler;
		}
	}

	private function start( $m ) {
		$force_start = isset( $m['bot_command'] ) && $m['bot_command']['cmd'] == '/start';
		$res = $this->model->user->get( $m['from']['id'] );

		if( count($res) == 0 ) {
			$this->model->user->create( $m['from'], $force_start );
		}
		else {
			$this->model->user->update( $m['from'], $force_start );
		}
	}

	private function getType( $m ) {
		$type = NULL;
		if( isset($m['message'] )) { 
			$m = $m['message'];
			$m = $this->getEntities( $m );
			if ( isset ($m['bot_command']) ) $type = EventType::Command;
			else if ( isset($m['new_chat_members']) ) $type = EventType::NewChatMembers;
			else if ( isset($m['left_chat_member']) ) $type = EventType::LeftChatMember;
			else if ( isset($m['group_chat_created']) ) $type = EventType::NewGroupCreated;
			else if ( isset($m['migrate_from_chat_id']) && $m['chat']['type'] == "supergroup" ) $type = EventType::UpgradedToSupergroup;
			else $type = EventType::Message;
		}
		else if( isset($m['location']) ) {
			$type = EventType::processLocation;
		}
		else if( isset($m['inline_query']) ) {
			$m = $m['inline_query'];
			$type = EventType::InlineQuery;
		}
		else if ( isset($m['callback_query']) ) {
			$type = EventType::CallbackQuery;
			$m['callback_query']['message']['reply_to_message'] = $this->getEntities($m['callback_query']['message']['reply_to_message']);
		}

		return [$m, $type];
	}

	private function getEntities( $m ) {
		if( !isset($m['entities']) ) return $m;
		$cmd = NULL;
		foreach ( $m['entities'] as &$entity ) {
			$entity['entity'] = mb_substr( $m['text'], $entity['offset'], $entity['length'] );
			if ($entity['type'] == "bot_command" && $entity['offset'] == 0) { 
				$m['bot_command'] = [
					'cmd' => $this->cleanCommand( $entity['entity'] ),
					'txt' => trim(mb_substr($m['text'], $entity['length']))
				];
			} 
		}
		unset($entity);
		return $m;
	}

	private function cleanCommand( $cmd ) {
		$cmd = mb_strtolower($cmd);
		$username = "@" . $this->telegram->username;
		return str_replace($username, "", $cmd);
	}
}
