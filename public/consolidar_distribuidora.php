<!DOCTYPE html>
<html>
    <body>
    </div class="orcamento">
                <form action="index.php?requisicao=cotacao" method="POST"> 
                  

                    <table>
                        <tr>
                            <th><label for="produto">Produto</label></th>
                            <th><label for="quantidade">Qtde M³ (min. 3)</label></th>
                            <th><label for="Frete">Frete</label></th>
                            <?php
                                if($_SESSION['permissao'] >= 5){
                                    echo "<th><label for='id_dist'>ID Distribuidora</label></th>";
                                    echo "<th><label for='cnpj_dist'>CNPJ Distribuidora</label></th>";

                                    
                                    
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>Etanol</td>
                            <td><input type="number" id="quantidade_etanol" required="required" name="quantidade_etanol" value=0></td>
                            <td>
                                <input type="radio" id="frete_etanol" required="required" name="frete_etanol" value="CIF" checked="true">
                                <label for="frete_etanol">CIF</label>
                                <input type="radio" id="frete_etanol" required="required" name="frete_etanol" value="FOB">
                                <label for="frete_etanol">FOB</label>
                            </td>
                            <?php
                                if($_SESSION['permissao'] >= 5){
                                    echo "<td><input type='text' id='id_dist_etanol' required='required' name='valor_dist_etanol'  step ='.01'></td>";
                                    echo "<td><input type='text' id='cnpj_dist_etanol' required='required' name='valor_dist_etanol'  step ='.01'></td>";
                                    
                                    
                                    
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>Gasolina</td>
                            <td><input type="number" id="quantidade_gasolina" required="required" name="quantidade_gasolina" value=0></td>
                            <td>
                                <input type="radio" id="frete_gasolina" required="required" name="frete_gasolina" value="CIF" checked="true">
                                <label for="frete_gasolina">CIF</label>
                                <input type="radio" id="frete_gasolina" required="required" name="frete_gasolina" value="FOB">
                                <label for="frete_gasolina">FOB</label>
                            </td>
                            <?php
                                if($_SESSION['permissao'] >= 5){
                                    echo "<td><input type='text' id='id_dist_gasolina' required='required' name='valor_dist_gasolina'  step ='.01'></td>";
                                    echo "<td><input type='text' id='cnpj_dist_gasolina' required='required' name='valor_dist_gasolina'  step ='.01'></td>";
                                    
                                    
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>Diesel</td>
                            <td><input type="number" id="quantidade_diesel" required="required" name="quantidade_diesel" value=0></td>
                            <td>
                                <input type="radio" id="frete_diesel" required="required" name="frete_diesel" value="CIF" checked="true">
                                <label for="frete_diesel">CIF</label>
                                <input type="radio" id="frete_diesel" required="required" name="frete_diesel" value="FOB">
                                <label for="frete_diesel">FOB</label>
                            </td>
                            <?php
                                if($_SESSION['permissao'] >= 5){
                                    echo "<td><input type='text' id='id_dist_diesel' required='required' name='valor_dist_diesel'  step ='.01'></td>";
                                    echo "<td><input type='text' id='cnpj_dist_diesel' required='required' name='valor_dist_diesel'  step ='.01'></td>";
                                    
                                }
                            ?>
                        </tr>
                    </table>
                    <input  type="submit"  class="enviar" onclick="Enviar();" value="salvar"/>
                </form>  
    </body>



</html>