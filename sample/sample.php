<?php
require_once 'log/log.class.php';
$_NYAA_LOG = array();
$logger = new NyaaLog( );
$handler = $logger->createHandler('capture');
$handler->bind($_NYAA_LOG);
$logger->addHandler(NyaaLog::ALL, $handler);
$logger->debug("DEBUG MESSAGE");
$logger->info("INFO MESSAGE");
$logger->notice("NOTICE MESSAGE");
$logger->warning("WARNING MESSAGE");
$logger->error("ERROR MESSAGE");
foreach($_NYAA_LOG as $log) echo '<li>'.$log.'</li>';
?>
