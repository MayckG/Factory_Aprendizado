<!DOCTYPE HTML>
<html>
    <head>
        <title>Factory</title>
        <meta charset="utf-8">
    </head>

    <body>

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

            }


            //Execução Geral, independente do método POST
            $conn = servidor();

            if($conn->connect_error)
                die("Falha na conexão: " . $conn->connect_error);

            //Pesquisa os pedidos consolidados por distribuidora
            $sql = "SELECT 
                        c.regiao,
                        d.fantasia,
                        pci.produto,
                        SUM(pci.quantidade) as quantidade,
                        pci.frete                
                    FROM (
                            SELECT f.id, f.fantasia, 'Sul' as regiao FROM fornecedor f WHERE f.regiao_sul = 'S'
                            UNION ALL
                            SELECT f.id, f.fantasia, 'Sudeste' as regiao FROM fornecedor f WHERE f.regiao_sudeste = 'S'
                            UNION ALL
                            SELECT f.id, f.fantasia, 'Centro-Oeste' as regiao FROM fornecedor f WHERE f.regiao_centro_oeste = 'S'
                            UNION ALL
                            SELECT f.id, f.fantasia, 'Nordeste' as regiao FROM fornecedor f WHERE f.regiao_nordeste = 'S'
                            UNION ALL
                            SELECT f.id, f.fantasia, 'Norte' as regiao FROM fornecedor f WHERE f.regiao_norte = 'S'     
                            ) d 
                    LEFT OUTER JOIN cliente c ON c.regiao = d.regiao
                    INNER JOIN pedido_cotacao pc ON pc.fk_cliente_id = c.id
                    INNER JOIN pedido_cotacao_item pci ON pc.id = pci.fk_Pedido_Cotacao_id
                    WHERE pc.situacao = 'A'
                      AND pci.situacao = 'B'
                    GROUP BY d.fantasia, c.regiao, pci.produto, pci. frete
                    ORDER BY c.regiao, d.fantasia, pci.produto, pci.frete";
            
            //Executa a query SQL dentro da conexão com o BD configurada em $conn
            $ps = $conn->prepare($sql);
            //$ps->bind_param("ss", "A", "B" );
            $ps->execute();
            $result = $ps->get_result();
            echo "<table>";
            echo "    <tr>";
            echo "        <th id='tblTextCenter'>Região</th>";
            echo "        <th id='tblTextCenter'>Distribuidora</th>";
            echo "        <th id='tblTextCenter'>Produto</th>";
            echo "        <th id='tblTextCenter'>Qtde m³</th>";
            echo "        <th id='tblTextCenter'>Frete</th>";
            echo "    </tr>";
            if( $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "    <td id='tblTextLeft'>" . $row['regiao'] . "</td>";
                    echo "    <td id='tblTextLeft'>" . $row['fantasia'] . "</td>";
                    echo "    <td id='tblTextCenter'>" . $row['produto'] . "</td>";
                    echo "    <td id='tblTextRight'>" . $row['quantidade'] . "</td>";
                    echo "    <td id='tblTextCenter'>" . $row['frete'] . "</td>";
                    echo "    </td>";
                    echo "</tr>";
                }
            }
            echo "</table>";

        ?> 

    </body>
</html>
