<?php
    include('fswdd.php'); //fswdd - Funções do Sistema Web Definidas pelo Desenvolvedor

    //Regras de permissão
    //1 = Cliente
    //5 = Vendedor
    //8 = Financeiro
    //9 = Administrador

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    if(!isset($_SESSION['login']))
        header("Location: index.php");


    if($_SERVER['REQUEST_METHOD']=="POST"){

        $conn = servidor();
    
        if($conn->connect_error)
            die("Falha na conexão: " . $conn->connect_error);

        $prazo = $_POST['prazo'];
        
        //Quantidade
        $etanol = $_POST['quantidade_etanol'];
        $gasolina = $_POST['quantidade_gasolina'];
        $diesel = $_POST['quantidade_diesel'];

        //Frete
        $frete_etanol = $_POST['frete_etanol'];
        $frete_gasolina = $_POST['frete_gasolina'];
        $frete_diesel = $_POST['frete_diesel'];
        
        $erro_combustivel = 0;

        //validação de quantidade e frete dos combustíveis
        if(($etanol > 0 && $frete_etanol === "none") || ($etanol === 0 && $frete_etanol != "none")){
            echo "<p>Erro: quantidade ou frete não informado para Etanol.</p>";
            $erro_combustivel ++;
        }
        if(($gasolina > 0 && $frete_gasolina === "none") || ($gasolina === 0 && $frete_gasolina != "none")){
            echo "<p>Erro: quantidade ou frete não informado para Gasolina.</p>";
            $erro_combustivel ++;
        }
        if(($diesel > 0 && $frete_diesel === "none") || ($diesel === 0 && $frete_diesel != "none")){
            echo "<p>Erro: quantidade ou frete não informado para Diesel.</p>";
            $erro_combustivel ++;
        }
        if($erro_combustivel > 0){
            echo "<br>";
            //echo "<p>retornar para página anterior <a href='index.php?requisicao=cotacao'>voltar</a> </p>";
            echo "<p>retornar para página anterior <a href='javascript:history.back()'>voltar</a> </p>";
            exit();
        }

        if($prazo > 0 && ($etanol >= 3 || $gasolina >= 3 || $diesel >= 3) ){

            $sql = "INSERT INTO pedido_cotacao (datahora, situacao, fk_cliente_id)
                    VALUES (CURRENT_TIMESTAMP(),'A',". $_SESSION['id'] .")";
            $result = $conn->query($sql);  //Executa a query SQL dentro da conexão com o BD configurada em $conn
            //Se query foi executada
            if( $result === TRUE){
                $id_pedido_cotacao = $conn->insert_id;
                if($id_pedido_cotacao > 0){
                    //PRAZO
                    $sql = "INSERT INTO pedido_cotacao_prazo (fk_Pedido_Cotacao_id, prazo_dias, porcentual)
                            VALUES (". $id_pedido_cotacao .",". $prazo .", 100.0)";
                    $result = $conn->query($sql);  //Executa a query SQL dentro da conexão com o BD configurada em $conn
                    if( $result === FALSE)
                        echo "Erro ao incluir prazo de pagamento: ". $sql . "<br>" . $conn->error;
                    
                    //ETANOL
                    if($etanol >= 3){
                        $sql = "INSERT INTO pedido_cotacao_item (fk_Pedido_Cotacao_id, produto, quantidade, frete, situacao)
                                VALUES (". $id_pedido_cotacao .",'Etanol',". $etanol .",'". $_POST['frete_etanol'] ."', 'A')";
                        $result = $conn->query($sql);  //Executa a query SQL dentro da conexão com o BD configurada em $conn
                        if( $result === FALSE)
                            echo "Erro incluir etanol: ". $sql . "<br>" . $conn->error;
                    }

                    //GASOLINA
                    if($gasolina >= 3){
                        $sql = "INSERT INTO pedido_cotacao_item (fk_Pedido_Cotacao_id, produto, quantidade, frete, situacao)
                                VALUES (". $id_pedido_cotacao .",'Gasolina',". $gasolina .",'". $_POST['frete_gasolina'] ."', 'A')";
                        $result = $conn->query($sql);  //Executa a query SQL dentro da conexão com o BD configurada em $conn
                        if( $result === FALSE)
                            echo "Erro incluir gasolina: ". $sql . "<br>" . $conn->error;
                    }

                    //DIESEL
                    if($diesel >= 3){
                        $sql = "INSERT INTO pedido_cotacao_item (fk_Pedido_Cotacao_id, produto, quantidade, frete, situacao)
                                VALUES (". $id_pedido_cotacao .",'Diesel',". $diesel .",'". $_POST['frete_diesel'] ."', 'A')";
                        $result = $conn->query($sql);  //Executa a query SQL dentro da conexão com o BD configurada em $conn
                        if( $result === FALSE)
                            echo "Erro incluir diesel: ". $sql . "<br>" . $conn->error;
                    }

                    $_SESSION['id_pedido_cotacao'] = $id_pedido_cotacao;
                    header("Location: index.php?requisicao=cotacao_cadastrada");
                    /*
                    echo "<br>";
                    echo "<p>Pedido de cotação cadastrado com sucesso.</p>";
                    echo "<p>Seu número de pedido: ". $id_pedido_cotacao ."</p>";
                    echo "<br>";
                    */
                }
            }
            //Se query não foi executada
            else
                echo "Erro ao incluir pedido: ". $sql . "<br>" . $conn->error;
        }
        else{
            echo "<p>Erro: Informe a quantidade do combustível maior que 3 m³.</p>";
            exit();
        }
    }

?>

<!DOCTYPE HTML>

<html>
    <head>
    <title>Factory</title>
    </head>
    <link rel="stylesheet" type="text/css" href="estilos/estilo.css">
    <meta charset="utf-8">

    <body>
        </div class="orcamento">
            <form action="index.php?requisicao=cotacao" method="POST"> 
                <table>
                    <tr>
                        <th>Prazo (1 - 60)</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" id="prazo" required="required" name="prazo" min = 1 max = 60 placeholder="Dias de prazo">
                        </td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th><label for="produto">Produto</label></th>
                        <th><label for="quantidade">Qtde M³ (min. 3)</label></th>
                        <th><label for="Frete">Frete</label></th>
                        <?php
                            if($_SESSION['permissao'] >= 5){
                                echo "<th><label for='valor_dist'>Valor Distribuidora</label></th>";
                                echo "<th><label for='valor_adm'>Taxa Administrativa</label></th>";
                                echo "<th><label for='valor_fin'>Taxa Financeira</label></th>";
                                echo "<th><label for='valor_uni'>Valor Unitário</label></th>";
                                echo "<th><label for='valor_total'>Valor Total</label></th>";
                            }
                        ?>
                    </tr>
                    <tr>
                        <td>Etanol</td>
                        <td><input type="number" id="quantidade_etanol" required="required" name="quantidade_etanol" placeholder="qtde m³"></td>
                        <td>
                            <select name="frete_etanol">
                                <option value="none"></option>
                                <option value="CIF">CIF</option>
                                <option value="FOB">FOB</option>
                            </select>
                        </td>
                        <?php
                            if($_SESSION['permissao'] >= 5){
                                echo "<td><input type='number' id='valor_dist_etanol' required='required' name='valor_dist_etanol' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_adm_etanol' required='required' name='valor_adm_etanol' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_fin_etanol' required='required' name='valor_fin_etanol' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_uni_etanol' required='required' name='valor_uni_etanol' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_total_etanol' required='required' name='valor_total_etanol' value=0 step ='.01'></td>";
                            }
                        ?>
                    </tr>
                    <tr>
                        <td>Gasolina</td>
                        <td><input type="number" id="quantidade_gasolina" required="required" name="quantidade_gasolina" placeholder="qtde m³"></td>
                        <td>
                            <select name="frete_gasolina">
                                <option value="none"></option>
                                <option value="CIF">CIF</option>
                                <option value="FOB">FOB</option>
                            </select>
                        </td>
                        <?php
                            if($_SESSION['permissao'] >= 5){
                                echo "<td><input type='number' id='valor_dist_gasolina' required='required' name='valor_dist_gasolina' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_adm_gasolina' required='required' name='valor_adm_gasolina' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_fin_gasolina' required='required' name='valor_fin_gasolina' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_uni_gasolina' required='required' name='valor_uni_gasolina' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_total_gasolina' required='required' name='valor_total_gasolina' value=0 step ='.01'></td>";
                            }
                        ?>
                    </tr>
                    <tr>
                        <td>Diesel</td>
                        <td><input type="number" id="quantidade_diesel" required="required" name="quantidade_diesel" placeholder="qtde m³"></td>
                        <td>
                            <select name="frete_diesel">
                                <option value="none"></option>
                                <option value="CIF">CIF</option>
                                <option value="FOB">FOB</option>
                            </select>
                        </td>
                        <?php
                            if($_SESSION['permissao'] >= 5){
                                echo "<td><input type='number' id='valor_dist_diesel' required='required' name='valor_dist_diesel' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_adm_diesel' required='required' name='valor_adm_diesel' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_fin_diesel' required='required' name='valor_fin_diesel' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_uni_diesel' required='required' name='valor_uni_diesel' value=0 step ='.01'></td>";
                                echo "<td><input type='number' id='valor_total_diesel' required='required' name='valor_total_diesel' value=0 step ='.01'></td>";
                            }
                        ?>
                    </tr>
                </table>
                <input  type="submit"  class="enviar" onclick="Enviar();" value="salvar"/>
            </form>    
        </div>
    </body>
</html>
