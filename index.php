<?php
/*
    * ┌──────────────────────────────────────────┐
    * │   FRANCIS STUDIOS SOFTWARE  |    2026    │
    * ├──────────────────────────────────────────┤
    * │  OSS Project : Open for use & remix      │
    * │  GitHub      : github.com/francisstudios │
    * │  Author      : © Francis Studios by L.   │
    * └──────────────────────────────────────────┘
*/
require_once __DIR__ . "/src/modules/database-connection.module.php"; // This connects to db and establishes DB connection
include "./src/modules/database-installer.module.php"; // This installs db structure into your DB manager 

$pdo = DatabaseConnection::getConnection();

?>