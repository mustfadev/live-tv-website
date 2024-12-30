<?php

    $host       = 'localhost'; //Host: Usually, it’s localhost for CPanel
    $user       = 'root'; //Username 
    $pass       = ''; //Password: the password you set for the user.
    $database   = 'ksy'; //database

    $connect = new mysqli($host, $user, $pass, $database);

    if (mysqli_connect_errno()) {
        die ('Whoops! failed to connect to database : ' . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8mb4');
    }

?>

<?php $coca = 10; ?>