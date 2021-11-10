<!--Formulário de Contato (se não tem, criar)
Validação do preenchimento de todos os campos - OK
Alerta de campo não preenchido ao clicar em enviar OK
Validar preenchimento de todos os campos no servidor ok
Página de agradecimento do envio personalizada com nome do usuário ok
Página dinâmica para Serviços (produtos ou algo similar) OK
Criar links individuais, na página inicial, dos serviços oferecidos contendo o ID na URL OK
Receber ID como parâmetro da URL na página de serviços OK
Mostrar conteúdo diferente de acordo com o ID fornecido OK
Mostrar página de erro para ID não esperado OK-->


<?php 
    
    if (!isset($_GET['requisicao'])){
        header("Location: index.php?requisicao=login");
        //header("Location: login.php");
        }

    //inicia session em php
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    
    // se o tempo atual for maior que o tempo de logout
    if(isset($_SESSION['id_acesso'])){
        if(time() >= $_SESSION['logout_time']){
            header("location: index.php?requisicao=logout&mot=auto"); //vai para logout
        }
    }
?> 


<!DOCTYPE HTML>

<html>
    <head>
        <title>Factory</title>
        <link rel="stylesheet" type="text/css" href="estilos/estilo.css"/>
        <meta charset="utf-8"/>
        <link rel="shortcut icon" type="image/x-icon" href="imagens/favicon.ico">


        <header>
            <div class="container">
                <div id="logo">
                    <img src="imagens/logo.png">
                </div>
                <div id="menu">
                    <?php 
                        if(isset($_SESSION['login'])){
                            //administrador
                            if($_SESSION['permissao'] === 9){
                                echo "<a href='index.php?requisicao=painel'>Painel de Controle</a>";
                                //echo "<a href='index.php?requisicao=cotacao'>Cotação</a>";
                                echo "<a href='index.php?requisicao=consolidado'>Consolidado</a>";
                                echo "<a href='index.php?requisicao=meus_pedidos'>Meus Pedidos</a>";
                                echo "<a href='index.php?requisicao=financeiro'>Financeiro</a>";
                                //echo "<a href='index.php?requisicao=distribuidora'>Distribuidora</a>";
                                //echo "<a href='index.php?requisicao=relatorio'>Relatórios</a>";
                                echo "<a href='index.php?requisicao=logout'>Sair</a>";
                            }
                            //Financeiro
                            if($_SESSION['permissao'] === 8){
                                echo "<a href='index.php?requisicao=painel'>Painel de Controle</a>";
                                //echo "<a href='index.php?requisicao=cotacao'>Cotação</a>";
                                //echo "<a href='index.php?requisicao=consolidado'>Consolidado</a>";
                                echo "<a href='index.php?requisicao=meus_pedidos'>Meus Pedidos</a>";
                                echo "<a href='index.php?requisicao=financeiro'>Financeiro</a>";
                                //echo "<a href='index.php?requisicao=distribuidora'>Distribuidora</a>";
                                //echo "<a href='index.php?requisicao=relatorio'>Relatórios</a>";
                                echo "<a href='index.php?requisicao=logout'>Sair</a>";
                            }
                            //vendedor
                            else if($_SESSION['permissao'] === 5){
                                echo "<a href='index.php?requisicao=painel'>Painel de Controle</a>";
                                echo "<a href='index.php?requisicao=cotacao'>Cotação</a>";
                                echo "<a href='index.php?requisicao=consolidado'>Consolidado</a>";
                                echo "<a href='index.php?requisicao=meus_pedidos'>Meus Pedidos</a>";
                                //echo "<a href='index.php?requisicao=financeiro'>Financeiro</a>";
                                //echo "<a href='index.php?requisicao=distribuidora'>Distribuidora</a>";
                                //echo "<a href='index.php?requisicao=relatorio'>Relatórios</a>";
                                echo "<a href='index.php?requisicao=logout'>Sair</a>";
                            }
                            //cliente
                            else if($_SESSION['permissao'] === 1){
                                echo "<a href='index.php?requisicao=painel'>Painel de Controle</a>";
                                echo "<a href='index.php?requisicao=cotacao'>Cotação</a>";
                                //echo "<a href='index.php?requisicao=consolidado'>Consolidado</a>";
                                echo "<a href='index.php?requisicao=meus_pedidos'>Meus Pedidos</a>";
                                //echo "<a href='index.php?requisicao=financeiro'>Financeiro</a>";
                                //echo "<a href='index.php?requisicao=distribuidora'>Distribuidora</a>";
                                //echo "<a href='index.php?requisicao=relatorio'>Relatórios</a>";
                                echo "<a href='index.php?requisicao=logout'>Sair</a>";
                            }
                        }
                    ?>
                </div>
            </div>
        </header>
    </head>

    <body>
        
        <div id="usuario">
            
            <?php 
                if(isset($_SESSION['login']))
                    if($_SESSION['permissao'] === 1)
                        echo "<p>Bem vindo(a), <strong>". $_SESSION['usuario'] ."</strong> [CNPJ ". $_SESSION['cnpj'] ." - ". $_SESSION['razao_social'] ." - ANP ". $_SESSION['anp'] ."]</p>";
                    else
                        echo "<p>Bem vindo(a), <strong>". $_SESSION['usuario'] ."</strong></p>";
            ?>
        </div>
        <div id="tela">
            <?php
                //echo "<h5>" . strtoupper($_GET['requisicao']). "</h5>";
                switch($_GET['requisicao']){
                    case "cap":
                        $_SESSION['pagina'] = "Contas a Pagar";
                        break;    
                    case "car":
                        $_SESSION['pagina'] = "Contas a Receber";
                        break;
                    case "cliente":
                        $_SESSION['pagina'] = "Cadastro de Cliente";
                        break;
                    case "consolidar_distribuidora":
                        $_SESSION['pagina'] = "Consolidar Cotação da Distribuidora";
                        break;
                    case "consolidado":
                        $_SESSION['pagina'] = "Consolidado dos Pedidos de Cotação";
                        break;
                    case "cotacao":
                        $_SESSION['pagina'] = "Cadastro de Pedido de Cotação";
                        break;
                    case "cotacao_cadastrada":
                        $_SESSION['pagina'] = "Cotação Cadastrada";
                        break;
                    case "distribuidora":
                        $_SESSION['pagina'] = "Cotação com Distribuidoras";
                        break;
                    case "enviar_distribuidora":
                        $_SESSION['pagina'] = "Enviar Cotação para Distribuidora";
                        break;
                    case "financeiro":
                        $_SESSION['pagina'] = "Financeiro";
                        break;    
                    case "fornecedor":
                        $_SESSION['pagina'] = "Cadastro de Fornecedor";
                        break;
                    case "login":
                        unset($_SESSION['pagina']);
                        break;
                    case "logout":
                        unset($_SESSION['pagina']);
                        break;
                    case "log_funcao":
                        unset($_SESSION['pagina']);
                        break;
                    case "meus_clientes":
                        $_SESSION['pagina'] = "Clientes Cadastrados";
                        break;
                    case "meus_fornecedores":
                        $_SESSION['pagina'] = "Fornecedores Cadastrados";
                        break;
                    case "meus_pedidos":
                        $_SESSION['pagina'] = "Meus Pedidos";
                        break;
                    case "pedido":
                        $_SESSION['pagina'] = "Pedido";
                        break;
                    case "painel":
                        $_SESSION['pagina'] = "Painel de Controle";
                        break;    
                    case "relatorio":
                        $_SESSION['pagina'] = "Relatórios";
                        break;
                    case "usuario_cadastrado":
                        unset($_SESSION['pagina']);
                        break;
                    case "usuario_log":
                        $_SESSION['pagina'] = "Log de Acessos do usuário";
                        break;
                    case "usuarios":
                        $_SESSION['pagina'] = "Cadastro de Usuário";
                        break;
                    default:
                        unset($_SESSION['pagina']);
                }
                if(isset($_SESSION['pagina'])){
                    echo "<h3>" . $_SESSION['pagina']. "</h3>";
                }
            ?>
        </div>
        <div >
            <div id="principal">
                <div id="teste">
            <p></p>
                    <?php
                        switch($_GET['requisicao']){
                            case "cap":
                                include(__DIR__."/contas_pagar.php");
                                break;    
                            case "car":
                                include(__DIR__."/contas_receber.php");
                                break;
                            case "cliente":
                                include(__DIR__."/cliente.php");
                                break;
                            case "consolidar_distribuidora":
                                include(__DIR__."/consolidar_distribuidora.php");
                                break;
                            case "consolidado":
                                include(__DIR__."/consolidado.php");
                                break;
                            case "cotacao":
                                include(__DIR__."/cotacao.php");
                                break;
                            case "cotacao_cadastrada":
                                include(__DIR__."/cotacao_cadastrada.php");
                                break;
                            case "distribuidora":
                                include(__DIR__."/cotacao_distribuidora.php");
                                break;
                            case "enviar_distribuidora":
                                include(__DIR__."/enviar_cotacao_distribuidora.php");
                                break;
                            case "financeiro":
                                include(__DIR__."/financeiro.php");
                                break;    
                            case "fornecedor":
                                include(__DIR__."/fornecedor.php");
                                break;
                            case "login":
                                include(__DIR__."/login.php");
                                break;
                            case "logout":
                                include(__DIR__."/logout.php");
                                break;
                            case "log_funcao":
                                include(__DIR__."/log_fun.php");
                                break;
                            case "meus_clientes":
                                include(__DIR__."/meus_clientes.php");
                                break;
                            case "meus_fornecedores":
                                include(__DIR__."/meus_fornecedores.php");
                                break;
                            case "meus_pedidos":
                                include(__DIR__."/meus_pedidos.php");
                                break;
                            case "pedido":
                                include(__DIR__."/pedido.php");
                                break;
                            case "painel":
                                include(__DIR__."/controle.php");
                                break;    
                            case "relatorio":
                                include(__DIR__."/relatorios.php");
                                break;
                            case "usuario_cadastrado":
                                include(__DIR__."/usuario_cadastrado.php");
                                break;
                            case "usuario_log":
                                include(__DIR__."/log_acesso_usuario.php");
                                break;
                            case "usuarios":
                                include(__DIR__."/usuarios.php");
                                break;
                            default:
                                include(__DIR__."/404.php");
                        }
                    ?>
                </div>
        </div>
        </div>
    </body>
    <footer>
        <h6>Desenvolvido por Factory <br> 2021</h6></footer>
</html>