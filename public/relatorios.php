<?php 
    if (!isset($_GET['requisicao']))
        header("Location: index.php?requisicao=login");

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

?> 

<!DOCTYPE html>
<html>
    <header>
        <title>Em Produção</title>
        <link rel="stylesheet" type="text/css" href="estilos/estilo.css">
    </header>
    <body>
        <h1>EM PRODUÇÃO</h1>
    </body>
</html>