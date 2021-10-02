var btn = document.querySelector('pass');


function login_senha(){
    var senha = document.getElementById('pass');
    if(length(senha) >= 4)
        //document.getElementById("btnEntrar").style.display = "none";
        window.alert("oi");
    else
        //document.getElementById("btnEntrar").style.display = "block";
        window.alert("tchau");
}

btn.addEventListener(onkeypress, login_senha);
