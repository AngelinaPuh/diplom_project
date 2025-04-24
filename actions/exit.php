<?php
    session_start();
    session_destroy();
    // echo " $dbcon->error";
    header("Location: ../index.php");
?>