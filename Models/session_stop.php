<?php
 if (session_status () == PHP_SESSION_NONE)
 {
    session_start ();
 }


 session_destroy();

 header ("Location: ../index.php");