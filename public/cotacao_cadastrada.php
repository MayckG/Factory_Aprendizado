<?php
    echo "<br>";
    echo "<p>Pedido de cotação cadastrado com sucesso.</p>";
    echo "<p>Seu número de pedido: ". $_SESSION['id_pedido_cotacao'] ."</p>";
    echo "<br>";
    echo "<p>Voltar para o sistema <a href='index.php?requisicao=meus_pedidos'>voltar</a> </p>";
    unset($_SESSION['id_pedido_cotacao']);
?>