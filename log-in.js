function validate(){
    button = document.getElementById("log-in");
    email = document.getElementById("email").value;
    pwd = document.getElementById("pwd").value;
    if(email !== "" && pwd !== ""){
        button.disabled = false;
    } 
}