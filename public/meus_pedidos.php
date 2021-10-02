<!DOCTYPE HTML>
<html>
    <head>
        <title>Factory</title>
        <meta charset="utf-8">
    </head>
    <body>

        <div class="cadastro">
            <div id="controle">
                <a href="index.php?requisicao=meus_pedidos&sit=A">Aguardando Cotação</a>
                <a href="index.php?requisicao=meus_pedidos&sit=B">Enviado para Distribuidora</a>
                <a href="index.php?requisicao=meus_pedidos&sit=C">Cotado</a>
                <a href="index.php?requisicao=meus_pedidos&sit=P">Pedido do Cliente</a>
                <a href="index.php?requisicao=meus_pedidos&sit=R">Faturado</a>
                <a href="index.php?requisicao=meus_pedidos&sit=X">Cancelado</a>
                <a href="index.php?requisicao=meus_pedidos&sit=Z">Finalizado</a>
            </div>
        </div>

        <br>

        <div>
            <?php 
                include('fswdd.php'); //fswdu - Funções Sistema Web Definidas pelo Desenvolvedor

                if (!isset($_GET['requisicao']))
                    header("Location: index.php?requisicao=login");

                //inicia session em php
                if (session_status() == PHP_SESSION_NONE)
                    session_start();

                //Se sessão 'login' não estiver iniciada igual a true, volta para a página de login
                if(!isset($_SESSION['login']))
                    header("Location: index.php?requisicao=logout");

                //Se não metodo GET não estiver setado
                if(!isset($_GET['sit']))
                    exit();
                else
                    $situacao = $_GET['sit'];

                //Subtitulo da página
                switch($situacao){
                    case 'A':
                        $subtitulo = "Aguardando Cotação";
                        break;
                    case 'B':
                        $subtitulo = "Enviado para Distribuidora";
                        break;
                    case 'C':
                        $subtitulo = "Cotado";
                        break;
                    case 'P':
                        $subtitulo = "Pedido do Cliente";
                        break;
                    case 'R':
                        $subtitulo = "Faturado";
                        break;
                    case 'X':
                        $subtitulo = "Cancelado";
                        break;
                    case 'Z':
                        $subtitulo = "Finalizado";
                        break;
                    default:
                        $subtitulo = "";
                }
                echo "<p>". $subtitulo ."</p>";

                //Inicia conexão com o servidor
                $conn = servidor();

                //Se sessão de cliente
                if($_SESSION['permissao'] < 5){
                    $sql = "SELECT
                                pc.id,
                                pc.datahora,
                                pci.produto,
                                pci.quantidade,
                                pci.frete,
                                CASE 
                                    WHEN pci.situacao = 'A' THEN 'Aguardando' 
                                    WHEN pci.situacao = 'B' THEN 'Cotacao Distribuidora' 
                                    WHEN pci.situacao = 'C' THEN 'Cotado'
                                    WHEN pci.situacao = 'P' THEN 'Pedido'
                                    WHEN pci.situacao = 'R' THEN 'Faturado'
                                    WHEN pci.situacao = 'X' THEN 'Cancelado'
                                    WHEN pci.situacao = 'Z' THEN 'Finalizado'
                                END AS situacao
                            FROM pedido_cotacao pc
                            INNER JOIN pedido_cotacao_item pci ON pc.id = pci.fk_Pedido_Cotacao_id
                            WHERE pc.situacao = 'A'
                              AND pci.situacao = ?
                              AND pc.fk_cliente_id = ?
                            ORDER BY pc.id, pci.produto;";
                }
                //Senão, sessão de vendedor ou administrador
                else{
                    $sql = "SELECT
                                c.cnpj,
                                c.razao_social,
                                pc.id,
                                pc.datahora,
                                pci.produto,
                                pci.quantidade,
                                pci.frete,
                                CASE 
                                    WHEN pci.situacao = 'A' THEN 'Aguardando' 
                                    WHEN pci.situacao = 'B' THEN 'Cotacao Distribuidora' 
                                    WHEN pci.situacao = 'C' THEN 'Cotado'
                                    WHEN pci.situacao = 'P' THEN 'Pedido'
                                    WHEN pci.situacao = 'R' THEN 'Faturado'
                                    WHEN pci.situacao = 'X' THEN 'Cancelado'
                                    WHEN pci.situacao = 'Z' THEN 'Finalizado'
                                END AS situacao
                            FROM pedido_cotacao pc
                            INNER JOIN pedido_cotacao_item pci ON pc.id = pci.fk_Pedido_Cotacao_id
                            LEFT OUTER JOIN cliente c ON c.id = pc.fk_cliente_id
                            WHERE pc.situacao = 'A'
                               AND pci.situacao = ?
                            ORDER BY c.razao_social, pc.id, pci.produto;";
                }
                $ps = $conn->prepare($sql);
                //adicionar filtro cliente, quando permissão logada for menor que 5
                if($_SESSION['permissao'] < 5)
                    $ps->bind_param("si", $situacao, $_SESSION['id'] );
                else
                    $ps->bind_param("s", $situacao);
                $ps->execute();
                $result = $ps->get_result();

                echo "<table>";
                echo "    <tr>";
                if($_SESSION['permissao'] >= 5){
                    echo "        <th id='tblTextCenter'>CNPJ</th>";
                    echo "        <th id='tblTextCenter'>Razão Social</th>";    
                }
                echo "        <th id='tblTextCenter'>Pedido</th>";
                echo "        <th id='tblTextCenter'>Data</th>";
                echo "        <th id='tblTextCenter'>Produto</th>";
                echo "        <th id='tblTextCenter'>Qtde m³</th>";
                echo "        <th id='tblTextCenter'>Frete</th>";
                echo "        <th id='tblTextCenter'>Situação</th>";
                echo "    </tr>";
                if( $result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<tr>";
                        if($_SESSION['permissao'] >= 5){
                            echo "    <td id='tblTextLeft'>" . $row["cnpj"] . "</td>";
                            echo "    <td id='tblTextLeft'>" . $row["razao_social"] . "</td>";
                        }
                        echo "    <td id='tblTextCenter'>" . $row["id"] . "</td>";
                        echo "    <td id='tblTextLeft'>" . date('d/m/Y H:i:s', strtotime($row["datahora"])) . "</td>";
                        echo "    <td id='tblTextCenter'>" . $row["produto"] . "</td>";
                        echo "    <td id='tblTextRight'>" . $row["quantidade"] . "</td>";
                        echo "    <td id='tblTextCenter'>" . $row["frete"] . "</td>";
                        echo "    <td id='tblTextCenter'>" . $row["situacao"] . "</td>";
                        echo "    </td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";

            ?> 
        </div>

    </body>
</html>
