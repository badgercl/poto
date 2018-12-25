<?php

abstract class AbstractModel {
    protected $db;

    function __construct($db) {
        $this->db = $db;
    }
}

