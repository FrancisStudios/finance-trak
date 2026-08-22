<!-- 
* ┌──────────────────────────────────────────┐
* │   FRANCIS STUDIOS SOFTWARE  |    2026    │
* ├──────────────────────────────────────────┤
* │  OSS Project : Open for use & remix      │
* │  GitHub      : github.com/francisstudios │
* │  Author      : © Francis Studios by L.   │
* └──────────────────────────────────────────┘
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>finance-trak 26.01</title>
    <link rel="stylesheet" href="./src/assets/bootstrap.min.css">
    <link rel="stylesheet" href="./src/style.css">
</head>

<body>

    <?php
    require_once __DIR__ . "/src/modules/database-connection.module.php"; // This connects to db and establishes DB connection
    include __DIR__ . "/src/login.php";

    $pdo = DatabaseConnection::getConnection();
    ?>

    <span class="badge rounded-pill text-bg-light" id="bg-auth">©Francis Studios Softwares <a href="https://github.com/FrancisStudios/finance-trak">GitHub</a></span>

    <script>
        /**
         * Autologin if user session object can be retrieved
         * -------------------------------------------------
         */
        let userSession = sessionStorage.getItem('ftses');
        if (userSession && JSON.parse(userSession)) {

            session = JSON.parse(userSession);

            if (session.username && session.password) {
                console.log("Attempting auto login...");

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "./src/endpoints/login.endpoint.php", true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.send(JSON.stringify({
                    username: session.username,
                    password: session.password
                }));


                xhr.onreadystatechange = function() {
                    if (this.readyState != 4) return;
                    if (this.status == 200) {
                        let data = JSON.parse(this.responseText);
                        window.location.href = './src/main.php';
                    }
                };
            }
        }
    </script>
</body>

</html>