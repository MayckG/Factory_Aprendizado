<?php
    include_once('fswdd.php'); //fswdd - Funções do Sistema Web Definidas pelo Desenvolvedor

    $senha = "1234";
    $login = "abcd";

    $senha = senha($senha);

    //atualiza usuário
    $conn = servidor();
    if($conn->connect_error)
        die("Falha na conexão: " . $conn->connect_error);    $sql = "UPDATE usuario SET senha = ? WHERE login = ?";
    $ps = $conn->prepare($sql);
    $ps->bind_param("ss", $senha, $login);
    $ps->execute();
    $result = $ps->get_result();

    echo "Login ". $login ." atualizado.";
?>