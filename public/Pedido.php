<?php 
    if (!isset($_GET['requisicao']))
        header("Location: index.php?requisicao=login");

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

?> 

<!DOCTYPE HTML>

<html>
    <head>
        <title>Factory</title>
        <link rel="stylesheet" type="text/css" href="estilos/estilo.css">
        <meta charset="utf-8">
    </head>
    
    <body>
        <!--
        <div class="pedido">
            <div id=cadastro>
                <form id="cadastro" name="Cadastro" method="post" action="cadastro.php">
                    <table>
                        <tr>
                            <th><label for="CNPJ">CNPJ</label></th>
                            <th><label for="nome">Razão Social</label></th></tr>
                        </tr>
                        <tr>
                            <td><input type="text" id="codid" required="required" name="codigo"/></td>
                            <td><input type="text" id   ="nomeid" required="required" name="nome"/> </td></tr>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th><label for="ANP">Registro ANP</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="ANPid" required="required" name="ANP"/></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        -->

        
    </body>
</html>
