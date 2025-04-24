<?php
  $dbcon = new mysqli("localhost", "root", "", "diplom");
  
  if($dbcon->connect_errno) {
    printf("Не удалось подключиться: %s\n", $dbcon->connect_error);
    exit();
  }
?>
