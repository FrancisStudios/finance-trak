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

class DatabaseInstaller
{
    private static ?PDO $instance = null;

    public static function getInstaller($db, $host, $port, $charset, $credentials)
    {
        if (self::$instance === null) {

            $DEFAULT_DB = $db;


            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $dsnServer = "mysql:host={$host};port={$port};charset={$charset}";

                /* Create Server Without DB*/
                $pdo = new PDO(
                    $dsnServer,
                    $credentials['username'],
                    $credentials['password'],
                    $options
                );

                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$DEFAULT_DB` CHARACTER SET $charset COLLATE utf8mb4_unicode_ci;");
                $pdo->exec("USE `$DEFAULT_DB`;");

                /* Execute the Schema */
                self::executeSchema($pdo);

                self::$instance = $pdo;
            } catch (PDOException $e) {
                echo ("<h1>Error occured while installing DB</h1>");
                throw new Exception($e->getMessage());
            }
        }
        return self::$instance;
    }

    private static function executeSchema(PDO $pdo)
    {
        /**
         * Here you can define / redefine schema according to your
         * needs and ideas. This is the default data structure for
         * what I use (at least version 26.01)
         */
        $queries = [
            // Categories list <Helper> table
            "CREATE TABLE IF NOT EXISTS `categories` (
            `CID` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `Category` VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            // Priorities list <Helper> table
            "CREATE TABLE IF NOT EXISTS `priorities` (
            `PID` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `Priority` VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            // Users <System> table
            "CREATE TABLE IF NOT EXISTS `users` (
            `UID` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `Username` VARCHAR(191) NOT NULL UNIQUE,
            `Password` VARCHAR(255) NOT NULL,
            `Privileges` TINYINT UNSIGNED NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            // Transactions log table with Foreign Keys
            "CREATE TABLE IF NOT EXISTS `transactions` (
            `TID` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `Year` SMALLINT UNSIGNED NOT NULL,
            `Month` TINYINT UNSIGNED NOT NULL,
            `Day` TINYINT UNSIGNED NOT NULL,
            `Amount` INT NOT NULL,
            `Description` TEXT NOT NULL,
            `Category` INT UNSIGNED NOT NULL,
            `Priority` INT UNSIGNED NOT NULL,
            CONSTRAINT `fk_transactions_category` 
                FOREIGN KEY (`Category`) REFERENCES `categories` (`CID`) 
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT `fk_transactions_priority` 
                FOREIGN KEY (`Priority`) REFERENCES `priorities` (`PID`) 
                ON DELETE RESTRICT ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];

        foreach ($queries as $query) {
            $pdo->exec($query);
        }

        /**
         * Seed the admin user into the users table - so one can access the account
         * username: admin
         * password: 1234 -> hashed in sha256
         */
        $statement = $pdo->prepare("
            INSERT INTO `users` (`Username`, `Password`, `Privileges`)
            VALUES (:username, :password, :privileges)
            ON DUPLICATE KEY UPDATE `UID` = `UID`
        ");

        $statement->execute([
            'username'   => 'admin',
            'password'   => '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4',
            'privileges' => 15
        ]);
    }
}

?>
