<?php

class UserLog extends AbstractModel {

	public function get($uid, $limit=1) {
		$uid = $this->db->real_escape_string( $uid );
		$limit = (int) $limit;

		$sql = "SELECT * FROM logs_cmd WHERE uid=$uid ORDER BY created_at DESC LIMIT $limit";
		$res = DbConfig::sql($this->db, $sql);
		if(!$res) {
			error_log($sql);
		    error_log($this->db->error);
		}
		if($limit==1) return count($res)>0 ? $res[0] : NULL;
		else return count($res)>0 ? $res : NULL;

	}

	public function save($m) {
		$uid = $this->db->real_escape_string( $m['from']['id'] );
		$gid = $this->db->real_escape_string( $m['chat']['id'] );
		$command = $this->db->real_escape_string( $m['bot_command']['cmd'] );
		$args = $this->db->real_escape_string( $m['bot_command']['txt'] );
		$mid = $this->db->real_escape_string( $m['message_id'] );

		$sql = "INSERT INTO logs_cmd (uid, gid, message_id, command, args) VALUES ($uid, $gid, $mid, '$command', '$args')";
		if(!DbConfig::update($this->db, $sql)) {
			error_log($sql);
		    error_log($this->db->error);
		}
	}
}
