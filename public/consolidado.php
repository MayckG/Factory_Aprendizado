<?php 

    //fswdu - Funções Sistema Web Definidas pelo Desenvolvedor
    include_once('fswdd.php'); 

    //Se não houver requisição para a página específica, leva o usuário para a tela de login
    if (!isset($_GET['requisicao']))
        header("Location: index.php?requisicao=login");

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    //Se sessão 'login' não estiver iniciada igual a true, volta para a página de login
    if(!isset($_SESSION['login']))
        header("Location: index.php?requisicao=logout");

    //Procedimentos do método POST
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $conn = servidor();
    
        if($conn->connect_error)
            die("Falha na conexão: " . $conn->connect_error);

        //altera a situacação dos pedidos de cotação de 'A' para 'B'
        $sql = "UPDATE pedido_cotacao_item SET situacao = 'B'
                WHERE situacao = 'A'";
        //Executa a query SQL dentro da conexão com o BD configurada em $conn
        $result = $conn->query($sql);
        //Se query foi executada
        if( $result === TRUE){

            //$_SESSION['id_pedido_cotacao'] = $id_pedido_cotacao;
            header("Location: index.php?requisicao=enviar_distribuidora");
            /*
            echo "<br>";
            echo "<p>Pedido de cotação cadastrado com sucesso.</p>";
            echo "<p>Seu número de pedido: ". $id_pedido_cotacao ."</p>";
            echo "<br>";
            */
        }
        //Se query não foi executada
        else
            echo "Erro ao cadastrar consolidação: ". $sql . "<br>" . $conn->error;
        exit();
    }


    //Execução Geral, independente do método POST
    $conn = servidor();

    $sql = "SELECT
                c.regiao,
                pci.produto,
                SUM(pci.quantidade) as quantidade,
                pci.frete
            FROM pedido_cotacao pc
            INNER JOIN pedido_cotacao_item pci ON pc.id = pci.fk_Pedido_Cotacao_id
            LEFT OUTER JOIN cliente c ON c.id = pc.fk_cliente_id
            WHERE pc.situacao = 'A'
              AND pci.situacao = 'A'
            GROUP BY c.regiao, pci.produto, pci. frete
            ORDER BY c.regiao, pci.produto, pci.frete;";
    $ps = $conn->prepare($sql);
    //$ps->bind_param("i", $_SESSION['id'] );
    $ps->execute();
    $result = $ps->get_result();

    echo "<form action='index.php?requisicao=consolidado' method='POST'>";
    echo "  <table>";
    echo "        <tr>";
    echo "            <th id='tblTextCenter'>Região</th>";
    echo "          <th id='tblTextCenter'>Produto</th>";
    echo "            <th id='tblTextCenter'>Qtde m³</th>";
    echo "            <th id='tblTextCenter'>Frete</th>";
    echo "      </tr>";
    if( $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "  <tr>";
            echo "        <td id='tblTextLeft'>" . $row["regiao"] . "</td>";
            echo "      <td id='tblTextLeft'>" . $row["produto"] . "</td>";
            echo "        <td id='tblTextRight'>" . $row["quantidade"] . "</td>";
            echo "      <td id='tblTextCenter'>" . $row["frete"] . "</td>";
            echo "      </td>";
            echo "  </tr>";
        }
    }
    echo "  </table>";
    echo "  <input  type='submit'  class='enviar' onclick='Enviar();' value='Consolidar'/>";
    echo "</form>";

?> 


<!DOCTYPE HTML>

<html>
    <head>
        <title>Factory</title>
        <meta charset="utf-8">
    </head>

    <body>

    </body>
</html>
