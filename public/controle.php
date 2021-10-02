<?php 
    if (!isset($_GET['requisicao']))
        header("Location: index.php?requisicao=login");

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

?> 


<!DOCTYPE html>
<html>
   <head>
   <title>Factory</title>
        <link rel="stylesheet" type="text/css" href="estilos/estilo.css"/>
        <meta charset="utf-8"/>


   </head>
    <body>
        <div class="cadastro">
            <div id="controle">
                <?php 
                    if(isset($_SESSION['login'])){
                        //administrador
                        if($_SESSION['permissao'] === 9){
                            echo "<a href='index.php?requisicao=cliente'>Cadastro Cliente</a>";
                            echo "<a href='index.php?requisicao=fornecedor'>Cadastro Fornecedor</a>";
                            echo "<a href='index.php?requisicao=usuarios'>Cadastro Usuário</a>";
                            echo "<a href='index.php?requisicao=meus_clientes'>Todos os Clientes</a>";
                            echo "<a href='index.php?requisicao=meus_fornecedores'>Todos os Fornecedores</a>";
                            echo "<a href='index.php?requisicao=usuario_log'>Log de Acesso</a>";
                        }
                        //Financeiro
                        if($_SESSION['permissao'] === 8){
                            echo "<a href='index.php?requisicao=meus_clientes'>Todos os Clientes</a>";
                            echo "<a href='index.php?requisicao=meus_fornecedores'>Todos os Fornecedores</a>";
                        }
                        //Vendedor
                        if($_SESSION['permissao'] === 5){
                            echo "<a href='index.php?requisicao=cliente'>Cadastro Cliente</a>";
                            echo "<a href='index.php?requisicao=fornecedor'>Cadastro Fornecedor</a>";
                            echo "<a href='index.php?requisicao=meus_clientes'>Todos os Clientes</a>";
                            echo "<a href='index.php?requisicao=meus_fornecedores'>Todos os Fornecedores</a>";
                        }
                        //Cliente
                        if($_SESSION['permissao'] === 1){
                            echo "<a href='index.php?requisicao=usuario_log'>Log de Acesso</a>";
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>