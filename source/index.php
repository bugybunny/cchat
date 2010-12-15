<?php
/**
 * Hauptseite des Chats (Login, Registrierung, Chat)
 * @author Jannis <jannis@gje.ch>
 */
include "config.inc.php";
include "php/constants.php";
include "php/error.php";
header("Content-type: text/html; charset=utf-8");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>cchat <?php echo CCHAT_VERSION ?></title>
        <link rel="stylesheet" href="css/style.css">
        <script src="javascript/LAB.js"></script>
        <script>
            $LAB.script("javascript/mootools-core.js").wait()
            .script("javascript/xhr.class.js")
            .script("javascript/login.class.js")
            .script("javascript/chat.class.js").wait()
            .script("javascript/scripts.js");
        </script>
    </head>
    <body>
        <noscript><p>You need JavaScript to access cchat.</p></noscript>
        <section id="login">
            <form id="loginform" action="" method="post">
                <div>
                    <label for="name">Name</label>: <input id="name" type="text" name="name"><br>
                    <label for="name">Passwort</label>: <input id="password" type="password" name="password">
                </div>
                <div id="register">
                    <label for="name">Wiederholen</label>: <input id="password2" type="password" name="password2"><br>
                    <label for="name">E-Mail</label>: <input id="email" type="text" name="email"><br>
                </div>
                <div>
                    <input id="loginsubmit" type="submit" value="Login"> <a id="registertoggle" href="">Registrieren</a>
                </div>
            </form>
        </section>
        <section id="chat">
            <div id="chatmessages"></div>
            <div id="chatuser">
                <p>User online:</p>
                <ul id="chatuserlist"></ul>
            </div>
            <form id="chatform" action="" method="post">
                <p>
                    <input id="chattext" type="text" name="text" size="100" autocomplete="off">
                    <input type="submit" value="Go">
                    <input id="chatlogout" type="button" name="logout" value="Logout">
                </p>
            </form>
        </section>
</body>
</html>