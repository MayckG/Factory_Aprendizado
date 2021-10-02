
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

    $sql= "SELECT ID,
                PARCELA,
                VALOR,
                FK_NOTA_FISCAL_ID AS NOTA_FISCAL, 
                SITUACAO 
            FROM titulo_receber
            WHERE situacao='A'";

    $ps = $conn->prepare($sql);
    $ps->execute();
    $result = $ps->get_result();

        echo "<table>";
        echo "    <tr>";
        echo "        <th id='tblTextCenter'>ID</th>";
        echo "        <th id='tblTextCenter'>PARCELA</th>";
        echo "        <th id='tblTextCenter'>VALOR</th>";
        echo "        <th id='tblTextCenter'>NOTA FISCAL</th>";
        echo "        <th id='tblTextCenter'>SITUAÇÃO</th>";
        echo "    </tr>";
    if( $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "    <td id='tblTextCenter'>" . $row["ID"] . "</td>";
            echo "    <td id='tblTextRight'>" . $row["PARCELA"] . "</td>";
            echo "    <td id='tblTextCenter'>" . $row["VALOR"] . "</td>";
            echo "    <td id='tblTextCenter'>" . $row["NOTA_FISCAL"] . "</td>";
            echo "    <td id='tblTextCenter'>" . $row["SITUACAO"] . "</td>";
            echo "    </td>";
            echo "</tr>";
        }
    }
    echo "</table>";




?> 