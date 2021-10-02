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

        //Fornecedor
        $cnpj = $_POST['fornecedor_cnpj'];
        $razao = $_POST['fornecedor_razao'];
        $fantasia = $_POST['fornecedor_fantasia'];
        $ins_est = $_POST['fornecedor_ins_est'];
        $ins_mun = $_POST['fornecedor_ins_mun'];
        $contato = $_POST['fornecedor_contato'];
        $telefone = $_POST['fornecedor_telefone'];
        $email = $_POST['fornecedor_email'];

        $fornecedor_regiao = $_POST['fornecedor_regiao'];
        $regiao_sul = "N";
        $regiao_sudeste = "N";
        $regiao_centro_oeste = "N";
        $regiao_nordeste = "N";
        $regiao_norte = "N";
        $regiao_controle = FALSE;
        foreach($fornecedor_regiao as $regiao){
            if($regiao === "Sul"){
                $regiao_sul = "S";
                $regiao_controle = TRUE;
            }
            else if($regiao === "Sudeste"){
                $regiao_sudeste = "S";
                $regiao_controle = TRUE;
            }
            else if($regiao === "Centro-Oeste"){
                $regiao_centro_oeste = "S";
                $regiao_controle = TRUE;
            }
            else if($regiao === "Nordeste"){
                $regiao_nordeste = "S";
                $regiao_controle = TRUE;
            }
            else if($regiao === "Norte"){
                $regiao_norte = "S";
                $regiao_controle = TRUE;
            }
        }

        
            //Valida se todos os campos estão preenchidos na tela
        if($cnpj === "" || $razao === "" || $fantasia === "" || $ins_est === "" || $ins_mun === "" || !$regiao_controle || $contato === "" || $telefone === "" || $email === "" ){
            echo "<p>Antes de salvar, preencha todos os dados do cadastro de fornecedor.</p>";
            exit();
        }
        //Cadastro do fornecedor
        else{
            //Consulta CNPJ
            $sql = "SELECT id FROM fornecedor WHERE cnpj = ? ";
            $ps = $conn->prepare($sql);
            $ps->bind_param("s", $cnpj );
            $ps->execute();
            $result = $ps->get_result();
            if( $result->num_rows > 0){
                $row = $result->fetch_assoc();
                $id_fornecedor = $row['id'];
                echo "<br>";
                echo "<p>Cadastro de fornecedor já existe.</p>";
                echo "<p>ID do Cadastro: ". $id_fornecedor ."</p>";
                echo "<br>";
            }
            else{
                //Cadastrar Fornecedor
                $sql = "INSERT INTO fornecedor (cnpj, razao_social, fantasia, inscricao_estadual, inscricao_municipal, contato, telefone, email, regiao_sul, regiao_sudeste, regiao_centro_oeste, regiao_nordeste, regiao_norte)
                        VALUES ('". $cnpj ."', '". $razao ."', '". $fantasia ."', '". $ins_est ."', '". $ins_mun ."', '". $contato ."', '". $telefone ."', '". $email ."', '". $regiao_sul ."', '". $regiao_sudeste ."', '". $regiao_centro_oeste ."', '". $regiao_nordeste ."', '". $regiao_norte ."')";
                //Executa a query SQL dentro da conexão com o BD configurada em $conn
                $result = $conn->query($sql);
                //Se query foi executada
                if( $result === TRUE){
                    $id_fornecedor = $conn->insert_id;
                    if($id_fornecedor > 0){
                        echo "<br>";
                        echo "<p>Cadastro de fornecedor realizado com sucesso.</p>";
                        echo "<p>ID do Cadastro: ". $id_fornecedor ."</p>";
                        echo "<br>";
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
                <form class="cadastro" id="cadastro" name="Cadastro" action="index.php?requisicao=fornecedor" method="POST">
                    <p>Dados Gerais</p>
                    <table>
                        <tr>
                            <th><label for="fornecedor_cnpj">CNPJ</label></th>
                            <th><label for="fornecedor_razao">Razão Social</label></th>
                            <th><label for="fornecedor_fantasia">Nome Fantasia</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="fornecedor_cnpj" required="required" name="fornecedor_cnpj" maxlength=14/></td>
                            <td><input type="text" id="fornecedor_razao" required="required" name="fornecedor_razao" maxlength=50/></td>
                            <td><input type="text" id="fornecedor_fantasia" required="required" name="fornecedor_fantasia" maxlength=50/></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th><label for="fornecedor_ins_est">Inscrição Estadual</label></th>
                            <th><label for="fornecedor_ins_mun">Inscrição Municipal</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="fornecedor_ins_est" required="required" name="fornecedor_ins_est" maxlength=20/></td>
                            <td><input type="text" id="fornecedor_ins_mun" required="required" name="fornecedor_ins_mun" maxlength=20/></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th colspan=5><label for="fornecedor_regiao">Região</label></th>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="fornecedor_regiao" name="fornecedor_regiao[]" value="Sul" unchecked>
                                <label for="fornecedor_regiao">Sul</label>
                            </td>
                            <td>
                                <input type="checkbox" id="fornecedor_regiao" name="fornecedor_regiao[]" value="Sudeste" unchecked>
                                <label for="fornecedor_regiao">Sudeste</label>
                            </td>
                            <td>
                                <input type="checkbox" id="fornecedor_regiao" name="fornecedor_regiao[]" value="Centro-Oeste" unchecked>
                                <label for="fornecedor_regiao">Centro-Oeste</label>
                            </td>
                            <td>
                                <input type="checkbox" id="fornecedor_regiao" name="fornecedor_regiao[]" value="Nordeste" unchecked>
                                <label for="fornecedor_regiao">Nordeste</label>
                            </td>
                            <td>
                                <input type="checkbox" id="fornecedor_regiao" name="fornecedor_regiao[]" value="Norte" unchecked>
                                <label for="fornecedor_regiao">Norte</label>
                            </td>
                        </tr>
                    </table>

                    <p>Contato</p>
                    <table>
                        <tr>
                            <th><label for="fornecedor_contato">Nome</label></th>
                            <th><label for="fornecedor_telefone">Telefone</label></th>
                            <th><label for="fornecedor_email">E-mail</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="fornecedor_contato" required="required" name="fornecedor_contato" maxlength=50/></td>
                            <td><input type="tel" id="fornecedor_telefone" required="required" name="fornecedor_telefone" maxlength=11/></td>
                            <td><input type="email" id="fornecedor_email" required="required" name="fornecedor_email" maxlength=100/></td>
                         </tr>
                    </table>
                    
                    <td><input  type="submit"  class="enviar" onclick="Enviar();" value="Salvar"/></td>
                </form>
            </div>
        </div>
    </body>
</html>
