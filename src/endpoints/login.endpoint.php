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
include '../modules/database-connection.module.php';
header('Content-Type: application/json');

/**
 * On this endpoint only POST requests are allowed
 * -----------------------------------------------
 * @@@@@ other requests are promply rejected @@@@@
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$JSONRequestData = file_get_contents('php://input');
$data = json_decode($JSONRequestData, true);

/* If JSON Payload is invalid or corrupted, we reject it */
if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload']);
    exit;
}

$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

/* If username and password is present we allow the pass through */
if (empty($username) || empty($password)) {
    http_response_code(422);
    echo json_encode(['error' => 'Username and password are required']);
    exit;
}

/**
 * Database verifications are here, if you need to modify it
 * this is the right place for it ^^ ***********************
 * ---------------------------------------------------------
 */

$password = hash('sha256', $password); //not very secure on this side
//but plenty good for home lab

$pdo = DatabaseConnection::getConnection();
$query = $pdo->prepare('SELECT UID, Username, Password, Privileges FROM users WHERE username = :username AND password = :password LIMIT 1');
$query->execute([
    ':username' => $username,
    ':password' => $password
]);

$user = $query->fetch();

if ($user && credentialVerification($user, $username, $password)) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'user'    => [
            'UID'        => (int)$user['UID'],
            'username'   => $user['Username'],
            'password'   => $user['Password'],
            'privileges' => $user['Privileges']
        ]
    ]);
    exit;
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error'   => 'Invalid credentials'
    ]);
    exit;
}

function credentialVerification($user, $username, $password)
{
    return ($user['Username'] == $username) && ($user['Password'] == $password);
}
?>