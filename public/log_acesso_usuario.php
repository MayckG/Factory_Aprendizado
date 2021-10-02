<!DOCTYPE HTML>
<html>
    <head>
        <title>Factory</title>
        <meta charset="utf-8">
    </head>
    <body>
        <!--
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
        -->

        <div>
            <?php 
                include('fswdd.php'); //fswdu - Funções Sistema Web Definidas pelo Desenvolvedor

                if (!isset($_GET['requisicao']))
                    header("Location: index.php?requisicao=login");

                //inicia session em php
                if (session_status() == PHP_SESSION_NONE)
                    session_start();

                //Se sessão 'login' não estiver iniciada igual a true, ou sessão 'usuario_login' não estiver iniciada, volta para a página de login
                if(!isset($_SESSION['login']) || !isset($_SESSION['usuario_login']) )
                    header("Location: index.php?requisicao=logout");
                
                //Procedimentos do método POST
                if($_SERVER['REQUEST_METHOD']=="POST"){
                    //$_SESSION['id_pedido_cotacao'] = $id_pedido_cotacao;
                    header("Location: index.php?requisicao=log_funcao&obj=limpar");
                }

                //Execução Geral, independente do método POST
                //Inicia conexão com o servidor
                $conn = servidor();

                //Se sessão de cliente
                if($_SESSION['permissao'] < 5){
                    $sql = "SELECT 
                                a.id,
                                u.nome,
                                a.datahora,
                                a.ip_acesso,
                                a.hostname,
                                a.dt_logout,
                                a.motivo_logout 
                            FROM acesso a
                            LEFT OUTER JOIN usuario u ON u.id = a.fk_usuario_id 
                            WHERE a.fk_usuario_login = ?
                            ORDER BY a.datahora, a.fk_usuario_login;";
                }
                //Senão, sessão maiou ou igual a 5
                else{
                    $sql = "SELECT 
                                a.id,
                                u.nome,
                                a.datahora,
                                a.ip_acesso,
                                a.hostname,
                                a.dt_logout,
                                a.motivo_logout 
                            FROM acesso a
                            LEFT OUTER JOIN usuario u ON u.id = a.fk_usuario_id 
                            ORDER BY a.datahora, a.fk_usuario_login;";
                }
                $ps = $conn->prepare($sql);
                //adicionar filtro cliente, quando permissão logada for menor que 5
                if($_SESSION['permissao'] < 5)
                    $ps->bind_param("s", $_SESSION['usuario_login'] );
                $ps->execute();
                $result = $ps->get_result();

                if($_SESSION['permissao'] < 5)
                    echo "<form action='index.php?requisicao=usuario_log' method='POST'>";
                echo "  <table>";
                echo "      <tr>";
                echo "          <th id='tblTextCenter'>ID</th>";
                echo "          <th id='tblTextCenter'>Nome</th>";
                echo "          <th id='tblTextCenter'>Data Login</th>";
                echo "          <th id='tblTextCenter'>IP</th>";
                echo "          <th id='tblTextCenter'>Hostname</th>";
                echo "          <th id='tblTextCenter'>Data Logout</th>";
                echo "          <th id='tblTextCenter'>Motivo do Logout</th>";
                echo "      </tr>";
                if( $result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "      <tr>";
                        echo "          <td id='tblTextCenter'>" . $row["id"] . "</td>";
                        echo "          <td id='tblTextLeft'>" . $row["nome"] . "</td>";
                        echo "          <td id='tblTextCenter'>" . date('d/m/Y H:i:s', strtotime($row["datahora"])) . "</td>";
                        echo "          <td id='tblTextCenter'>" . $row["ip_acesso"] . "</td>";
                        echo "          <td id='tblTextLeft'>" . $row["hostname"] . "</td>";
                        if($row["dt_logout"] > 1)
                            echo "          <td id='tblTextCenter'>" . date('d/m/Y H:i:s', strtotime($row["dt_logout"])) . "</td>";
                        else
                            echo "          <td id='tblTextCenter'></td>";
                        echo "          <td id='tblTextLeft'>" . $row["motivo_logout"] . "</td>";
                        echo "    </tr>";
                    }
                }
                echo "  </table>";
                if($_SESSION['permissao'] < 5){
                    echo "  <input  type='submit'  class='enviar' onclick='Enviar();' value='Excluir log'/>";
                    echo "</form>";
                }
            ?> 
        </div>

    </body>
</html>
