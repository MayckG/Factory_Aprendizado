<?php
    include_once('fswdd.php'); //fswdd - Funções do Sistema Web Definidas pelo Desenvolvedor

    if (session_status() == PHP_SESSION_NONE)
        session_start();

    $conn = servidor();

    if($conn->connect_error)
        die("Falha na conexão: " . $conn->connect_error);

    //Controle de Acesso
    if(isset($_SESSION['id_acesso'])){
        if(!isset($_GET['mot']))
            $sql = "UPDATE acesso SET dt_logout = CURRENT_TIMESTAMP(), motivo_logout = 'Pelo usuário' WHERE id = ". $_SESSION['id_acesso'];
        else
            $sql = "UPDATE acesso SET dt_logout = CURRENT_TIMESTAMP(), motivo_logout = 'Sessão expirada' WHERE id = ". $_SESSION['id_acesso'];
        $result = $conn->query($sql);  //Executa a query SQL dentro da conexão com o BD configurada em $conn
        if( $result === FALSE)
            echo "Erro ao registrar logout: ". $sql . "<br>" . $conn->error;
    }

    session_unset();    //usado para limpar as variáveis da sessão. Usado quando há mais de uma variável.
    session_destroy();
    header("Location: index.php");
?>