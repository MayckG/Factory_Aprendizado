<?php 
    include_once('fswdd.php'); //fswdu - Funções Sistema Web Definidas pelo Desenvolvedor

    if (!isset($_GET['requisicao']))
        header("Location: index.php?requisicao=login");

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    //Se sessão 'login' não estiver iniciada igual a true, ou sessão 'usuario_login' não estiver iniciada, volta para a página de login
    if(!isset($_SESSION['login']) || !isset($_SESSION['usuario_login']) )
        header("Location: index.php?requisicao=logout");


    if (isset($_GET['obj']))
        $objeto = $_GET['obj'];
    else
        header("Location: index.php?requisicao=usuario_log");

    //Procedimentos do método POST
    if($_SERVER['REQUEST_METHOD']=="POST"){

        //Verifica opção informada pelo usuário na confirmação de exclusão
        $botao = $_POST['btn'];
        $btn_opcao = "";
        foreach($botao as $btn){
            if($btn === "Sim")
                $btn_opcao = "S";
            else if($btn === "Não")
                $btn_opcao = "N";
        }
        
        //Escolha igual a Sim
        if($btn_opcao === 'S'){//Deletar dados de acesso do usuário
            //Inicia conexão com o servidor
            $conn = servidor();

            $sql = "DELETE FROM acesso WHERE fk_usuario_login = ? ";
            //Executa a query SQL dentro da conexão com o BD configurada em $conn
            $ps = $conn->prepare($sql);
            $ps->bind_param("s", $_SESSION['usuario_login'] );
            $ps->execute();
            //Se query foi executada
            if( $ps->affected_rows > 0){
                    echo "<br>";
                    echo "<p>Exclusão de log realizada com sucesso.</p>";
                    echo "<p>". $ps->affected_rows ." registros apagados</p>";
                    echo "<br>";
                    echo "<p>Voltar para o sistema <a href='index.php?requisicao=usuario_log'>voltar</a> </p>";
            }
            else{
                echo "<p>Não existem registros para serem apagados.</p>";
                echo "<p>Voltar para o sistema <a href='index.php?requisicao=usuario_log'>voltar</a> </p>";
            }
        }
        else{//Escolha igual diferente de Sim
            header("Location: index.php?requisicao=usuario_log");
        }
    }
    else{   
        //Execução Geral, independente do método POST
        if($objeto = 'limpar'){
            echo "<p>Confirma operação de exclusão do log de acesso?</p>";
            echo "<form action='index.php?requisicao=log_funcao&obj=limpar' method='POST'>";
            echo "  <input  type='submit' id='btn' name='btn[]' value='Sim'/>";
            echo "  <input  type='submit' id='btn' name='btn[]' value='Não'/>";
            echo "</form>";
        }
    }
?>