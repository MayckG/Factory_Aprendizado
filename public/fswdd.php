<?php
    //fswdd - Funções do Sistema Web Definidas pelo Desenvolvedor
    function servidor(){
        $servidor ="localhost";
        $usuario = "AdminBD";
        $senha = "eCN9nG$@4Pv*X.-";
        $basededados = "factory_fuelly";
        $porta = "3306";
        $conn = new mysqli($servidor, $usuario, $senha, $basededados, $porta);
        
        if($conn->connect_error)
            die("Falha na conexão: " . $conn->connect_error);
        else
            return $conn;
    }

    function getRealIpAddr(){
        if (!empty ($_SERVER['HTTP_CLIENTIP'])) // verificar o ip do compartilhamento de internet
        {
            $ip = $_SERVER['HTTP_CLIENTIP'];
        }
        else if(!empty ($_SERVER['HTTP_X_FORWARDEDFOR'])) // para verificar se o ip é passado do proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDEDFOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function senha($senha){
        $custo = ['cost' => 8];
        $hash = password_hash($senha,  PASSWORD_DEFAULT, $custo); //a hash será diferente a cada execução
        return $hash;
    }
?>