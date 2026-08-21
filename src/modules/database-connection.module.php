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
$host = '127.0.0.1';
$db   = 'duegev-wiki';
$port = 3306;
$charset = 'utf8mb4';

$credentials = [];
$authFilePath = __DIR__ . '/../assets/dbauth.conf';

if (!file_exists($authFilePath)) {
    echo ("<h2>Oopsie</h2>
    Authentication file can't be loaded from: <i>{$authFilePath}</i><br/> 
    Please check if this file is available! <br/><br/>
    
    This error occured in <b>database-connection.module</b>");
    exit(0);
}


/**
 * Process the config file into the credentials[]
 * ----------------------------------------------
 */
$lines = file(
    $authFilePath,
    FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
);

foreach ($lines as $line) {
    $parts = explode(':', $line, 2);
    if (count($parts) === 2) {
        $credentials[trim($parts[0])] = trim($parts[1]);
    }
}

if (empty($credentials['username']) || empty($credentials['password'])) {
    echo (
        "<h2>It looks like dbauth.conf is empty</h2>
        Please confirm that src/assets/dbauth.conf is in a correct format. <br/><br/>
        It should contain: <br/><i>
        username:yourusername <br/> password:yourpassword </i>"
    );
    exit(0);
}

/**
 * Establish PDO connection for later DB queries uwu
 * ----------------------------------------------
 */
$dsn = "mysql:host={$host};
        dbname={$db};
        port={$port};
        charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO(
        $dsn,
        $credentials['username'],
        $credentials['password'],
        $options
    );
} catch (PDOException $e) {
    echo(
        "<h1>Error occured while establishing PDO MySQL connection!</h1>
        Please make sure that<b> the database server is running </b> and <br/>
        is avaliable at <b>{$host}:{$port}</b>"

    );
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

?>