<?php

    include_once('fswdd.php'); //fswdd - Funções do Sistema Web Definidas pelo Desenvolvedor

    //Regras de permissão
    //1 = Cliente
    //5 = Vendedor
    //8 = Financeiro
    //9 = Administrador

    //inicia session em php, se já não estiver iniciada
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    //se sessão 'login' não estiver iniciada, não permite entrar na tela de cadastro de fornecedor
    if(!isset($_SESSION['login']))
        header("Location: index.php");

        //não permite usuário com permissao inferior à nivel 5 realizar cadastro de fornecedor
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

        //Usuário
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $login = $_POST['user'];
        $senha = $_POST['senha'];
        $tipo = $_POST['tipo_user'];
        
        //Define código da permissão
        if($tipo === "Administrador")
            $permissao = 9;
        else if($tipo === "Financeiro")
            $permissao = 8;
        else if($tipo === "Vendedor")
            $permissao = 5;
        else
            $permissao = 0;

        //Valida se todos os campos estão preenchidos na tela
        if($nome === "" || $telefone === "" || $email === "" || $login === "" || $senha === "" || $permissao === 0){
            echo "<p>Antes de salvar, preencha todos os dados do cadastro de usuário.</p>";
            exit();
        }
        //Cadastro do usuário
        else{
            //Consulta login
            $sql = "SELECT id FROM usuario WHERE login = ? ";
            $ps = $conn->prepare($sql);
            $ps->bind_param("s", $login );
            $ps->execute();
            $result = $ps->get_result();
            if( $result->num_rows > 0){
                $row = $result->fetch_assoc();
                $id_usuario = $row['id'];
                header("Location: index.php?requisicao=usuario_cadastrado&res=N");
                /*
                echo "<br>";
                echo "<p>Cadastro de usuário com o login informado já existe.</p>";
                echo "<p>Utilize outro login para o cadastro do usuário.</p>";
                echo "<br>";
                */
            }
            else{
                //Codifica senha do usuário
                $senha = senha($senha);

                //Cadastrar usuário
                $sql = "INSERT INTO usuario (login, senha, nome, email, telefone, permissao)
                        VALUES ('". $login ."', '". $senha ."', '". $nome ."', '". $email ."', '". $telefone ."', ". $permissao .")";
                //Executa a query SQL dentro da conexão com o BD configurada em $conn
                $result = $conn->query($sql);
                //Se query foi executada
                if( $result === TRUE){
                    $id_usuario = $conn->insert_id;
                    if($id_usuario > 0){
                        $_SESSION['id_usuario'] = $id_usuario;
                        header("Location: index.php?requisicao=usuario_cadastrado&res=S");
                        /*
                        echo "<br>";
                        echo "<p>Cadastro de usuário realizado com sucesso.</p>";
                        echo "<p>ID do Cadastro: ". $id_usuario ."</p>";
                        echo "<br>";
                        */
                    }
                    else
                        echo "Erro ao recuperar id do fornecedor cadastrado: ". $sql . "<br>" . $conn->error;
                }
                //Se query não foi executada
                else
                    echo "Erro ao incluir cadastro de fornecedor: ". $sql . "<br>" . $conn->error;
            }        
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
                <form class="cadastro" id="cadastro" name="Cadastro" method="post" action="index.php?requisicao=usuarios">
                    <table>
                        <tr>
                            <th><label for="nome">Nome </label></th>
                            <th><label for="telefone">Telefone </label></th>
                            <th><label for="email">e-Mail </label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="nome" required="required" name="nome"/></td>
                            <td><input type="tel" id="telefone" required="required" name="telefone"/> </td>
                            <td><input type="email" id="email" required="required" name="email"/> </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th><label for="user">Usuário</label></th>
                            <th><label for="senha">Senha</label></th>
                            <th><label for="tipo_user">Tipo Usuário</th>
                        </tr>
                        <tr>
                            <td><input type="text" id="user" required="required" name="user"/></td>
                            <td><input type="password" id="senha" required="required" name="senha"/></td>
                            <!--
                            <td>
                                <input type="radio" id="tipo_user" name="tipo_user">
                                <label for="tipo_user">Administrador</label>
                                <input type="radio" id="tipo_user" name="tipo_user">
                                <label for="tipo_user">Financeiro</label>
                                <input type="radio" id="tipo_user" name="tipo_user">
                                <label for="tipo_user">Vendedor</label>
                            </td>
                            -->
                            <td>
                                <select name="tipo_user">
                                    <option value="none"></option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Financeiro">Financeiro</option>
                                    <option value="Vendedor">Vendedor</option>
                                </select>
                            </td>
                         </tr>
                    </table>
                    <td><input  type="submit"  class="enviar" onclick="Enviar();" value="Salvar"/></td>
                </form>
            </div>
        </div>
    </body>
</html>
