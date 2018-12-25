<?php
require_once("config.php");
require_once("app/core/PoTo.php");

$db = DbConfig::getConnection($db_host, $db_user, $db_pass, $db_name);
$model = new Model($db);
$telegram = new Telegram($token, $bot_username, $db, new HTTP());

$extras = [ ];

$poto = new PoTo( $telegram, $model, $extras );

$poto->addHandler( new Ping('/ping'), EventType::Command );
