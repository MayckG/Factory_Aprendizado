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

    $conn = servidor();

    $sql = "SELECT
                c.cnpj,
                c.razao_social,
                c.anp,
                c.regiao,
                co.contato,
                co.telefone,
                co.email
            FROM cliente c
            LEFT OUTER JOIN contato co ON co.fk_cliente_id = c.id AND co.fk_cliente_cnpj = c.cnpj AND co.departamento = 'Compras'
            ORDER BY c.cnpj, c.razao_social;";
    $ps = $conn->prepare($sql);
    //$ps->bind_param("i", $_SESSION['id'] );
    $ps->execute();
    $result = $ps->get_result();

    echo "<table>";
    echo "    <tr>";
    echo "        <th id='tblTextCenter'>CNPJ</th>";
    echo "        <th id='tblTextCenter'>Razão Social</th>";
    echo "        <th id='tblTextCenter'>ANP</th>";
    echo "        <th id='tblTextCenter'>Região</th>";
    echo "        <th id='tblTextCenter'>Contato</th>";
    echo "        <th id='tblTextCenter'>Telefone</th>";
    echo "        <th id='tblTextCenter'>E-mail</th>";
    echo "    </tr>";

    if( $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "    <td id='tblTextleft'>" . $row["cnpj"] . "</td>";
            echo "    <td id='tblTextleft'>" . $row["razao_social"] . "</td>";
            echo "    <td id='tblTextleft'>" . $row["anp"] . "</td>";
            echo "    <td id='tblTextleft'>" . $row["regiao"] . "</td>";
            echo "    <td id='tblTextleft'>" . $row["contato"] . "</td>";
            echo "    <td id='tblTextleft'>" . $row["telefone"] . "</td>";
            echo "    <td id='tblTextleft'>" . $row["email"] . "</td>";
            echo "    </td>";
            echo "</tr>";
        }
    }
    echo "</table>";

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
