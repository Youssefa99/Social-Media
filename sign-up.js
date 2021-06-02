function validate(){
    button = document.getElementById("log-in");
    email = document.getElementById("email").value;
    username = document.getElementById("username").value;
    pwd = document.getElementById("pwd").value;
    if(email !== "" && pwd !== "" && username !== ""){
        button.disabled = false;
    } 
}