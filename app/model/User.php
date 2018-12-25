<?php

class User extends AbstractModel {

	public function get( $uid ) {
		$uid = $this->db->real_escape_string( $uid );
		$sql = "SELECT * FROM users WHERE uid = '$uid'";
		$res = DbConfig::sql($this->db, $sql);
		if(!$res) {
			error_log($sql);
		    error_log($this->db->error);
		}
		return $res;
	}

	public function create( $from, $force_start ) {
		list( $uid, $first_name, $last_name, $username, $language_code ) = $this->clean( $from );

		$force_start = $force_start ? 1 : 0;
		$sql = "INSERT INTO users (uid, first_name, last_name, username, language_code, start) VALUES ('$uid', '$first_name', '$last_name', '$username', '$language_code', $force_start)";

		if(!DbConfig::update($this->db, $sql)) {
			error_log($sql);
			error_log($this->db->error);
		}
	}

	public function update( $from, $force_start ) {
		list( $uid, $first_name, $last_name, $username, $language_code ) = $this->clean( $from );

		$sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', username='$username', language_code='$language_code'";
		if( $force_start ) $sql .= ' ,start=1';
		$sql .= " WHERE uid='$uid'";

		if(!DbConfig::update($this->db, $sql)) {
			error_log($sql);
		    error_log($this->db->error);
		}
	}

	private function clean( $from ) {
		$uid = $this->db->real_escape_string($from['id']);
		$first_name = $this->db->real_escape_string( isset($from['first_name'] )? $from['first_name']:"undefined");
		$last_name = $this->db->real_escape_string( isset($from['last_name'] ) ? $from['last_name']:"undefined");
		$username = $this->db->real_escape_string( isset($from['username'] ) ? $from['username']:"undefined");
		$language_code = $this->db->real_escape_string( isset($from['language_code'] ) ? $from['language_code']:"AA");

		return [ $uid, $first_name, $last_name, $username, $language_code ];
	}
}