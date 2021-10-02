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
        <div class="pedido">
            <div id="cadastro">
                <form id="cadastro" name="Cadastro" method="post" action="cadastro.php">
                    <table>
                        <tr>
                            <th><label for="cod">Codigo</label></th>
                            <th><label for="nome">Razão Social</label></th></tr>
                        </tr>
                        <tr>
                            <td><input type="text" id="codid" required="required" name="codigo"/></td>
                            <td><input type="text" id   ="nomeid" required="required" name="nome"/> </td></tr>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th><label for="CNPJ">CNPJ</label></th>
                            <th><label for="IE">Insc. Estadual</label></th>
                            <th><label for="ANP">Registro ANP</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="razaoid" required="required" name="CNPJ"/></td>
                            <td><input type="text" id="IEid" required="required" name="IE"/></td>
                            <td><input type="text" id="ANPid" required="required" name="ANP"/></td>
                        </tr>
                        <tr>
                            <th><label for="Contato">Contato</label></th>
                            <th><label for="telefone">Telefone</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="contatoid" required="required" name="contato"/></td>
                            <td><input type="text" id="telefoneid" required="required" name="telefone"/></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div id="endereco">
                <form>
                    <table>
                        <tr>
                            <th><label for="cep">CEP</label></th>
                            <th><label for="cidade">Cidade</label></th>
                            <th><label for="bairro">Bairro</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id   ="cepid" required="required" name="cep"/> </td>
                            <td><input type="text" id   ="bairroid" required="required" name="bairro"/> </td>
                            <td><input type="text" id   ="cidadeid" required="required" name="cidade"/> </td>
                        </tr>
                        <tr>
                            <th><label for="endereco">Endereco</label></th>
                            <th><label for="estado">Estado</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="enderecoid" required="required" name="endereco"/> </td>
                            <td><input type="text" id="estadoid" required="required" name="estado"/> </td>
                        </tr>
                    </table>        
                </form>
            </div>
        </div>
    </body>
</html>
