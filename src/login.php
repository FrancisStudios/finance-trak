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
?>

<div id="login-screen" class="pattern-background">
    <div class="card text-bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header">finance-trak 26.01 | Sign in</div>
        <div class="card-body">
            <div class="input-group mb-1">
                <span class="input-group-text" id="basic-addon1">Username</span>
                <input type="text" class="form-control" placeholder="user" aria-label="username" aria-describedby="basic-addon1" id="username-field">
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text" id="basic-addon1">Password</span>
                <input type="password" class="form-control" placeholder="*****" aria-label="password" aria-describedby="basic-addon1" id="password-field">
            </div>
            <div class="input-group button-hold">
                <button type="button" class="btn btn-light" id="submit-login">Sign in</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', (_dcme) => {
        document
            .getElementById("submit-login")
            .addEventListener('click', (e) => {
                const username = document.getElementById('username-field').value;
                let password = document.getElementById('password-field').value;

                /* Encrypt password for the backend travels - DB also stores PW in SHA-256 */
                const passwordEncryptionBuffer = new TextEncoder().encode(password);
                crypto.subtle.digest('SHA-256', passwordEncryptionBuffer).then(
                    (_hashBuffer) => {
                        const hashArray = Array.from(new Uint8Array(_hashBuffer));

                        password = hashArray
                            .map(b => b.toString(16).padStart(2, '0'))
                            .join('');


                        /* Launch the XHR request to verify login */
                        const xhr = new XMLHttpRequest();
                        xhr.open("POST", "./src/endpoints/login.endpoint.php", true);
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.send(JSON.stringify({
                            username: username,
                            password: password
                        }));

                        /* If login successful, create the user session */
                        xhr.onreadystatechange = function() {
                            if (this.readyState != 4) return;

                            if (this.status == 200) {
                                let data = JSON.parse(this.responseText);

                                if (data.success) {
                                    let userSession = {
                                        username: data.user.username,
                                        password: data.user.password,
                                    }

                                    sessionStorage.setItem(
                                        'ftses',
                                        JSON.stringify(userSession)
                                    );

                                   window.location.href = './src/main.php';
                                }
                                console.log(data);
                            }
                        };

                    }
                );
            });
    });
</script>