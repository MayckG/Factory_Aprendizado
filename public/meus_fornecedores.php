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
                f.cnpj,
                f.razao_social,
                CASE WHEN IFNULL(f.regiao_sul,'') = 'S' THEN 'X' ELSE '' END AS regiao_sul,
                CASE WHEN IFNULL(f.regiao_sudeste,'') = 'S' THEN 'X' ELSE '' END AS regiao_sudeste,
                CASE WHEN IFNULL(f.regiao_centro_oeste,'') = 'S' THEN 'X' ELSE '' END AS regiao_centro_oeste,
                CASE WHEN IFNULL(f.regiao_nordeste,'') = 'S' THEN 'X' ELSE '' END AS regiao_nordeste,
                CASE WHEN IFNULL(f.regiao_norte,'') = 'S' THEN 'X' ELSE '' END AS regiao_norte,
                f.contato,
                f.telefone,
                f.email
            FROM fornecedor f
            ORDER BY f.cnpj, f.razao_social;";
    $ps = $conn->prepare($sql);
    //$ps->bind_param("i", $_SESSION['id'] );
    $ps->execute();
    $result = $ps->get_result();

    echo "<table>";
    echo "    <tr>";
    echo "        <th id='tblTextCenter'>CNPJ</th>";
    echo "        <th id='tblTextCenter'>Razão Social</th>";
    echo "        <th id='tblTextCenter'>Sul</th>";
    echo "        <th id='tblTextCenter'>Sudeste</th>";
    echo "        <th id='tblTextCenter'>Centro-Oeste</th>";
    echo "        <th id='tblTextCenter'>Nordeste</th>";
    echo "        <th id='tblTextCenter'>Norte</th>";
    echo "        <th id='tblTextCenter'>Contato</th>";
    echo "        <th id='tblTextCenter'>Telefone</th>";
    echo "        <th id='tblTextCenter'>E-mail</th>";
    echo "    </tr>";

    if( $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "    <td id='tblTextleft'>" . $row["cnpj"] . "</td>";
            echo "    <td id='tblTextleft'>" . $row["razao_social"] . "</td>";
            echo "    <td id='tblTextCenter'>" . $row["regiao_sul"] . "</td>";
            echo "    <td id='tblTextCenter'>" . $row["regiao_sudeste"] . "</td>";
            echo "    <td id='tblTextCenter'>" . $row["regiao_centro_oeste"] . "</td>";
            echo "    <td id='tblTextCenter'>" . $row["regiao_nordeste"] . "</td>";
            echo "    <td id='tblTextCenter'>" . $row["regiao_norte"] . "</td>";
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
