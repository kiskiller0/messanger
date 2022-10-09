<?php
if ($user = $_SESSION['username']) {
    echo $user . " is connected";
    if ($msg = $_POST['message']) {
        echo "msg: $msg";
    }
}
