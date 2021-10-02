function Enviar() {
    var nome = document.getElementById("nomeid");
  /*  if (nome.value != "") {
        alert('Obrigado sr(a) ' + nome.value + ' os seus dados foram encaminhados com sucesso');
    }*/
    var nome = document.getElementById("nomeid" );
    var fone = document.getElementById("foneid" );
    var mail = document.getElementById("emailid" );
    var area = document.getElementById("areaid" );
    if (nome.value ==""){
        alert('Campo Nome não pode ser vazio');
    }
    else if(fone.value==""){
        alert('Campo Telefone não pode ser vazio');
    }
    else if(mail.value == ""){
        alert('Campo E-mail não pode ser vazio');
    }
    else if(area.value ==""){
        alert('Campo de Texto não pode ser vazio');
    }
    else {
        alert('Obrigado sr(a) ' + nome.value + ' os seus dados foram encaminhados com sucesso');
    }
}

function login_senha(){
    var senha = document.getElementById("pass");
    if(length(senha) >= 4)
        document.getElementById("btnEntrar").style.display = "block";
    else
        document.getElementById("btnEntrar").style.display = "block";
}