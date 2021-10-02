<?php 
    if (!isset($_GET['requisicao']))
        header("Location: index.php?requisicao=login");

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

?> 