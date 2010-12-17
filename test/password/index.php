<?php

/** PBKDF2 Implementation (described in RFC 2898)
 *
 * @author Andrew Johnson (http://www.itnewb.com/v/Encrypting-Passwords-with-PHP-for-Storage-Using-the-RSA-PBKDF2-Standard)
 *
 *  @param string p password
 *  @param string s salt
 *  @param int c iteration count (use 1000 or higher)
 *  @param int kl derived key length
 *  @param string a hash algorithm
 *
 *  @return string derived key
 */
function pbkdf2($p, $s, $c, $kl, $a = 'sha256') {

    $hl = strlen(hash($a, null, true)); # Hash length
    $kb = ceil($kl / $hl);              # Key blocks to compute
    $dk = '';                           # Derived key
    # Create key
    for ($block = 1; $block <= $kb; $block++) {

        # Initial hash for this block
        $ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);

        # Perform block iterations
        for ($i = 1; $i < $c; $i++)

        # XOR each iterate
            $ib ^= ( $b = hash_hmac($a, $b, $p, true));

        $dk .= $ib; # Append iterated block
    }

    # Return derived key of correct length
    return substr($dk, 0, $kl);
}

$password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : "";
$salt = isset($_REQUEST["salt"]) ? $_REQUEST["salt"] : "";
$hash = "";
if (trim($password) && trim($salt)) {
    $pbkdf2 = pbkdf2($password, $salt, 1001, 64, "sha1");
    for ($i = 0; $i < strlen($pbkdf2); $i++) {
        $char = dechex(ord($pbkdf2{$i}));
        if (strlen($char) == 1) {
            $char = "0" . $char;
        }
        $hash .= $char;
    }
}
?>

<!DOCTYPE html>
<title>Test for PBKDF2</title>
<meta charset="utf-8">
<script src="crypto-sha1-hmac-pbkdf2.js"></script>
<script>
    window.onload = function() {
        var password = document.getElementById("password").value;
        var salt = document.getElementById("salt").value
        if(password && salt) {
            var hash = Crypto.PBKDF2(password, salt, 64, {iterations: 1001});
            document.getElementById("hash").textContent = hash;
        }
    }
</script>
<h1>Test for PBKDF2</h1>
<p>This script will send a clear text password and salt to the server,
    generate a password hash with PBKDF2 (sha1) with PHP,
    sends the password, the salt and the hash back to the browser
    and tries to do the same thing with JavaScript.
    Hopefully we'll get the same result with both languages ;-)</p>
<form method="post">
    <label>Password: <input id="password" name="password" value="<?php echo $password ?>"> (not masked!)</label><br>
    <label>Salt: <input id="salt" name="salt" value="<?php echo $salt ?>"</label><br>
    <input type="submit" value="test"><br><br>
    <output>
        Hash (PHP): <?php echo $hash ?><br>
        Hash (JavaScript): <span id="hash"></span>
    </output>
</form>
