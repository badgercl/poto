<?php

class CallbackLog extends AbstractModel {

	public function save( $m ) {
		$uid = $this->db->real_escape_string( $m['callback_query']['from']['id'] );
		$gid = $this->db->real_escape_string( $m['callback_query']['message']['chat']['id'] );
		$mid = $this->db->real_escape_string( $m['callback_query']['message']['message_id'] );

		$original_command = $this->db->real_escape_string( $m['callback_query']['message']['reply_to_message']['text'] );
		$original_mid = $this->db->real_escape_string( $m['callback_query']['message']['reply_to_message']['message_id'] );


		$sql = "INSERT INTO logs_callback (uid, gid, message_id, original_command, original_message_id) VALUES ($uid, $gid, $mid, '$original_command', $original_mid)";
		if(!DbConfig::update($this->db, $sql)) {
			error_log($sql);
		    error_log($this->db->error);
		}
	}
}
