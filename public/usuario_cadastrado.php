<?php
    if(!isset($_GET['res']))
        exit();

    //Cadastro de usuário realizado
    if($_GET['res'] === "S"){
        echo "<br>";
        echo "<p>Cadastro de usuário realizado com sucesso.</p>";
        echo "<p>ID do Cadastro: ". $_SESSION['id_usuario'] ."</p>";
        echo "<br>";
        unset($_SESSION['id_usuario']);
        //Voltar para uma página especificada pelo desenvolvedor
        echo "<p>Voltar para o sistema <a href='index.php?requisicao=usuarios'>voltar</a> </p>";
    }
    //Cadastro já existe
    else if($_GET['res'] === "N"){
        echo "<br>";
        echo "<p>Cadastro de usuário com o login informado já existe.</p>";
        echo "<p>Utilize outro login para o cadastro do usuário.</p>";
        echo "<br>";
        //Voltar para a página anterior do navegador
        echo "<p>Voltar para o sistema <a href='javascript:history.back()'>voltar</a> </p>";
    }
    else{
        header("Location: index.php?requisicao=erro");
    }
?>