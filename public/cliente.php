<?php

    include('fswdd.php'); //fswdd - Funções do Sistema Web Definidas pelo Desenvolvedor

    //Regras de permissão
    //1 = Cliente
    //5 = Vendedor
    //8 = Financeiro
    //9 = Administrador

    //inicia session em php, se já não estiver iniciada
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    //se sessão 'login' não estiver iniciada, não permite entrar na tela de cadastro de cliente
    if(!isset($_SESSION['login']))
        header("Location: index.php");

        //não permite usuário com permissao inferior à nivel 5 realizar cadastro de cliente
    if($_SESSION['permissao'] < 5){
        header("Location: index.php?requisicao=logout");
    }

    //se servidor receber uma requisição do método 'POST'
    if($_SERVER['REQUEST_METHOD']=="POST"){

        //inicia conexão com o servidor do BD
        $conn = servidor();
        
        //se houver erro na conexão com o BD informa o erro e encerra conexão
        if($conn->connect_error)
            die("Falha na conexão: " . $conn->connect_error);

        $erro_cliente = 0;

        //Usuário
        $login = $_POST['user_login'];
        $senha = $_POST['user_senha'];
        $nome = $_POST['user_nome'];
        $telefone = $_POST['user_telefone'];
        $email = $_POST['user_email'];
        if($login === "" || $senha === "" || $nome === "" || $telefone === "" || $email === "" ){
            echo "<p>Antes de salvar, preencha todos os dados do [Cadastro usuário].</p>";
            $erro_cliente++;
        }
        
        //Cliente
        $cnpj = $_POST['cliente_cnpj'];
        $razao = $_POST['cliente_razao'];
        $fantasia = $_POST['cliente_fantasia'];
        $ins_est = $_POST['cliente_ins_est'];
        $ins_mun = $_POST['cliente_ins_mun'];
        $anp = $_POST['cliente_anp'];
        $regiao = $_POST['cliente_regiao'];
        if($cnpj === "" || $razao === "" || $fantasia === "" || $ins_est === "" || $ins_mun === "" || $anp === "" || $regiao === "none" ){
            echo "<p>Antes de salvar, preencha todos os dados de [Dados Gerais].</p>";
            $erro_cliente++;
        }

        //Endereço
        $cep = $_POST['endereco_cep'];
        $logradouro = $_POST['endereco_logradouro'];
        $numero = $_POST['endereco_numero'];
        $complemento = $_POST['endereco_complemento'];
        $bairro = $_POST['endereco_bairro'];
        $cidade = $_POST['endereco_cidade'];
        $estado = $_POST['endereco_estado'];
        if($cep === "" || $logradouro === "" || $numero === "" || $complemento === "" || $bairro === "" || $cidade === "" || $estado === "" ){
            echo "<p>Antes de salvar, preencha todos os dados de [Endereço].</p>";
            $erro_cliente++;
        }
        else{
            //Consulta CEP
            $sql = "SELECT id FROM cep WHERE cep = ? ";
            $ps = $conn->prepare($sql);
            $ps->bind_param("s", $cep );
            $ps->execute();
            $result = $ps->get_result();
            if( $result->num_rows > 0){
                $row = $result->fetch_assoc();
                $id_cep = $row['id'];
            }
            else{
                //Cadastra CEP
                $sql = "INSERT INTO cep (cep, logradouro, bairro, cidade, estado)
                        VALUES ('". $cep ."', '". $logradouro ."', '". $bairro ."', '". $cidade ."', '". $estado ."')";
                //Executa a query SQL dentro da conexão com o BD configurada em $conn
                $result = $conn->query($sql);
                if( $result === TRUE)
                    $id_cep = $conn->insert_id;
                else
                    echo "Erro ao recuperar id do cadastra de CEP: ". $sql . "<br>" . $conn->error;
                    $erro_cliente++;
                }
        }

        if($erro_cliente > 0){
            echo "<br>";
            echo "<p>retornar para página anterior <a href='javascript:history.back()'>voltar</a> </p>";
            exit();
        }

        //Consulta Cliente
        $sql = "SELECT id FROM cliente WHERE cnpj = ? ";
        $ps = $conn->prepare($sql);
        $ps->bind_param("s", $cnpj );
        $ps->execute();
        $result = $ps->get_result();
        if( $result->num_rows > 0){
            $row = $result->fetch_assoc();
            $id_cliente = $row['id'];
            echo "<br>";
            echo "<p>Cadastro de cliente já existe.</p>";
            echo "<p>ID do Cadastro: ". $id_cliente ."</p>";
            echo "<br>";
        }
        else{
            //Cadastrar usuário
            $sql = "INSERT INTO usuario (login, senha, nome, email, telefone, regiao, permissao)
                    VALUES ('". $login ."', '". $senha ."', '". $nome ."', '". $email ."', '". $telefone ."', '". $regiao ."', 1)";        
            //Executa a query SQL dentro da conexão com o BD configurada em $conn
            $result = $conn->query($sql);
            //Se query foi executada
            if( $result === TRUE){
                $id_usuario = $conn->insert_id;
                if($id_usuario > 0){
                    //Cadastra Cliente
                    $sql = "INSERT INTO cliente (cnpj, razao_social, fantasia, anp, inscricao_estadual, inscricao_municipal, fk_usuario_id, fk_usuario_login)
                            VALUES ('". $cnpj ."', '". $razao ."', '". $fantasia ."', '". $anp ."', '". $ins_est ."', '". $ins_mun ."', '". $id_usuario ."', '". $login ."')";
                    //Executa a query SQL dentro da conexão com o BD configurada em $conn
                    $result = $conn->query($sql);
                    if( $result === TRUE){
                        $id_cliente = $conn->insert_id;
                        if($id_cliente > 0){
                            //Cadastra Contato
                            $sql = "INSERT INTO contato (contato, departamento, email, telefone, fk_cliente_id, fk_cliente_cnpj)
                                    VALUES ('". $nome ."', 'Compras', '". $email ."', '". $telefone ."', ". $id_cliente .", '". $cnpj ."')";
                            //Executa a query SQL dentro da conexão com o BD configurada em $conn
                            $result = $conn->query($sql);
                            if( $result === FALSE)
                                echo "Erro ao incluir cadastro do contato: ". $sql . "<br>" . $conn->error;

                            //Cadastra Endereço
                            $sql = "INSERT INTO endereco (fk_cep_id, fk_cep_cep, numero, complemento, tipo, principal, fk_cliente_id, fk_cliente_cnpj)
                                    VALUES ('". $id_cep ."', '". $cep ."', '". $numero ."', '". $complemento ."', 'Entrega', 'S', ". $id_cliente .", '". $cnpj ."')";
                            //Executa a query SQL dentro da conexão com o BD configurada em $conn
                            $result = $conn->query($sql);
                            if( $result === TRUE){
                                echo "<br>";
                                echo "<p>Cadastro de cliente realizado com sucesso.</p>";
                                echo "<p>ID do Cadastro: ". $id_cliente ."</p>";
                                echo "<br>";            
                            }
                            else
                                echo "Erro ao incluir cadastro do endereço: ". $sql . "<br>" . $conn->error;
                        }
                        else
                            echo "Erro ao recuperar id do cliente cadastrado: ". $sql . "<br>" . $conn->error;
                    }
                    else
                        echo "Erro ao incluir cadastro de cliente: ". $sql . "<br>" . $conn->error;
                }
                else
                    echo "Erro ao recuperar id do usuário cadastrado: ". $sql . "<br>" . $conn->error;
            }
            //Se query não foi executada
            else
                echo "Erro ao incluir cadastro de usuário: ". $sql . "<br>" . $conn->error;
        }
    }

?>


<!DOCTYPE HTML>

<html>
    <head>
        <title>Factory</title>
        <link rel="stylesheet" type="text/css" href="estilos/estilo.css">
        <meta charset="utf-8">
    </head>

    <body>
        <div class="usuario">
            <div id=cadastro>
                <form class="cadastro" id="cadastro" name="Cadastro" action="index.php?requisicao=cliente" method="POST">
                <p>Cadastro usuário</p>
                    <table>
                        <tr>
                            <th><label for="user_login">Login</label></th>
                            <th><label for="user_senha">Senha</label></th>
                            <th><label for="user_nome">Nome</label></th>
                            <th><label for="user_telefone">Telefone</label></th>
                            <th><label for="user_email">E-mail</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="user_login" required="required" name="user_login" maxlength=20/></td>
                            <td><input type="password" id="user_senha" required="required" name="user_senha" maxlength=10/></td>
                            <td><input type="text" id="user_nome" required="required" name="user_nome" maxlength=50/></td>
                            <td><input type="tel" id="user_telefone" required="required" name="user_telefone" maxlength=11/></td>
                            <td><input type="email" id="user_email" required="required" name="user_email" maxlength=100/></td>
                        </tr>
                    </table>

                    <p>Dados Gerais</p>
                    <table>
                        <tr>
                            <th><label for="cliente_cnpj">CNPJ</label></th>
                            <th><label for="cliente_razao">Razão Social</label></th>
                            <th><label for="cliente_fantasia">Nome Fantasia</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="cliente_cnpj" required="required" name="cliente_cnpj" maxlength=14/></td>
                            <td><input type="text" id="cliente_razao" required="required" name="cliente_razao" maxlength=50/></td>
                            <td><input type="text" id="cliente_fantasia" required="required" name="cliente_fantasia" maxlength=50/></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th><label for="cliente_ins_est">Inscrição Estadual</label></th>
                            <th><label for="cliente_ins_mun">Inscrição Municipal</label></th>
                            <th><label for="cliente_anp">ANP</label></th>
                            <th><label for="cliente_regiao">Região</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="cliente_ins_est" required="required" name="cliente_ins_est" maxlength=20/></td>
                            <td><input type="text" id="cliente_ins_mun" required="required" name="cliente_ins_mun" maxlength=20/></td>
                            <td><input type="text" id="cliente_anp" required="required" name="cliente_anp" maxlength=20/></td>
                            <td>
                                <select name="cliente_regiao" required="required">
                                    <option value="none"></option>
                                    <option value="Sul">Sul</option>
                                    <option value="Sudeste">Sudeste</option>
                                    <option value="Centro-Oeste">Centro-Oeste</option>
                                    <option value="Nordeste">Nordeste</option>
                                    <option value="Norte">Norte</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <p>Endereço</p>
                    <table>
                        <tr>
                            <th><label for="endereco_cep">CEP</label></th>
                            <th><label for="endereco_logradouro">Logradouro</label></th>
                            <th><label for="endereco_numero">Número</label></th>
                            <th><label for="endereco_complemento">Complemento</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="endereco_cep" required="required" name="endereco_cep" maxlength=8/></td>
                            <td><input type="text" id="endereco_logradouro" required="required" name="endereco_logradouro" maxlength=50/></td>
                            <td><input type="text" id="endereco_numero" required="required" name="endereco_numero" maxlength=8/></td>
                            <td><input type="text" id="endereco_complemento" required="required" name="endereco_complemento" maxlength=20/></td>
                         </tr>
                    </table>
                    <table>
                        <tr>
                            <th><label for="endereco_bairro">Bairro</th>
                            <th><label for="endereco_cidade">Cidade</label></th>
                            <th><label for="endereco_estado">Estado</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="endereco_bairro" required="required" name="endereco_bairro" maxlength=50/></td>
                            <td><input type="text" id="endereco_cidade" required="required" name="endereco_cidade" maxlength=50/></td>
                            <td><input type="text" id="endereco_estado" required="required" name="endereco_estado" maxlength=2/></td>
                         </tr>
                    </table>
                    <td><input  type="submit"  class="enviar" onclick="Enviar();" value="Salvar"/></td>
                </form>
            </div>
        </div>
    </body>
</html>
