<?php
    include_once('fswdd.php'); //fswdd - Funções do Sistema Web Definidas pelo Desenvolvedor

    //Regras de permissão
    //1 = Cliente
    //5 = Vendedor
    //9 = Administrador

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    if(isset($_SESSION['login']))
        header("Location: index.php?requisicao=relatorio");


    if($_SERVER['REQUEST_METHOD']=="POST"){

        $login = $_POST['user'];

        $conn = servidor();
    
        if($conn->connect_error)
            die("Falha na conexão: " . $conn->connect_error);
            
        $sql = "SELECT u.id as u_id, u.nome, u.login, u.senha, u.permissao, c.id as c_id, c.cnpj, c.razao_social, c.fantasia, c.anp
                FROM  usuario u
                LEFT OUTER JOIN cliente c on c.fk_usuario_id = u.id and c.fk_usuario_login = u.login
                WHERE u.login = ?;";
        $ps = $conn->prepare($sql);
        $ps->bind_param("s", $login );
        $ps->execute();
        $result = $ps->get_result();

        if( $result->num_rows > 0)
            $row = $result->fetch_assoc();
        else
            die("<p>Login não cadastrado.</p>");

        $u_id = $row["u_id"];
        $login = $row["login"];

        //Valida usuário e senha sem criptografia
        //if($_POST["user"] == $login && $_POST["pass"] == $row["senha"] ){

        //Valida usuario
        if($_POST["user"] == $login){

            //valida senha criptografada
            if( password_verify($_POST["pass"], $row["senha"]) ){
                $_SESSION['login'] = true;
                $_SESSION['usuario_login'] = $row["login"];
                $_SESSION['usuario'] = $row["nome"];
                $_SESSION['permissao'] = $row["permissao"];
                $_SESSION['id'] = $row["c_id"];
                $_SESSION['cnpj'] = $row["cnpj"];
                $_SESSION['razao_social'] = $row["razao_social"];
                $_SESSION['fantasia'] = $row["fantasia"];
                $_SESSION['anp'] = $row["anp"];
                //Controle de tempo sessão
                $_SESSION['start_login'] = time();
                $_SESSION['logout_time'] = $_SESSION['start_login'] + (60*30); //60 segundos * 30 minutos  

                //admin
                if($_SESSION['permissao'] === 9){
                    header("Location: index.php?requisicao=painel");
                }
                //financeiro
                else if($_SESSION['permissao'] === 8){
                    header("Location: index.php?requisicao=meus_pedidos");
                }
                //vendedor
                else if($_SESSION['permissao'] === 5){
                    header("Location: index.php?requisicao=consolidado");
                }
                //cliente
                else{
                    header("Location: index.php?requisicao=meus_pedidos");
                }

                //Controle de Acesso
                $ip = getRealIpAddr();
                $hn = gethostname();
                $sql = "INSERT INTO acesso (datahora, ip_acesso, hostname, fk_usuario_id, fk_usuario_login)
                        VALUES (CURRENT_TIMESTAMP(), '". $ip ."', '". $hn ."',". $u_id .", '". $login ."')";
                $result = $conn->query($sql);  //Executa a query SQL dentro da conexão com o BD configurada em $conn
                if( $result === TRUE)
                    $_SESSION['id_acesso'] = $conn->insert_id;
                else
                    echo "Erro ao registrar acesso: ". $sql . "<br>" . $conn->error;

                exit();
            }
            else{
                die("<p>Senha inválida.</p><br><p>Voltar para o sistema <a href='javascript:history.back()'>voltar</a> </p>");
            }
        }else{
            //informação incorreta
            echo "Dados incorretos, tente novamente";
       }
    }

?>

<!DOCTYPE HTML>

<html>
    <head>
    <title>Factory</title>
    </head>
    <link rel="stylesheet" type="text/css" href="estilos/estilo.css">
    <link rel="shortcut icon" type="image/x-icon" href="imagens/favicon.ico">
    <script src="login.js" type="text/javascript"></script>
    <meta charset="utf-8">
    
    <body>
        <div class="container_login" >
            
            <div class="login">
                <img  src="imagens/logo_inicial.png">
                <form action="login.php" method="POST">
                    <input type="text" id="user" name="user" placeholder="Usuario"/><br><br>
                    <input type="password" id="pass" name="pass" placeholder="Senha"/><br><br>
                    <input type="submit" id="btnEntrar" value="Entrar"/>
                </form>
            </div>
        </div>
    </body>
</html>