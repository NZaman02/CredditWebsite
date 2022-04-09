$(document).on("submit", "#register_form", function(e){
    e.preventDefault();
    var password1 = $("#password_1").val();
    var password2 = $("#password_2").val();
    document.getElementById("matching").innerHTML = "";
    document.getElementById("emailTaken").innerHTML = "";
    document.getElementById("usernameTaken").innerHTML = "";
    console.log(password1);
    console.log(password2);
    if (password1 == password2){
    $.ajax({
        method: "POST",
        url: "register.php",
        data: $(this).serialize(),
        success: function(data){ // data is what is echoed by php
            checkRegistration(data);
        },
        failure: function(data){
            failed_reg(data);
        }
    });
    } else {
        document.getElementById("matching").innerHTML = "passwords do not match";
    }
});

function login(){
    console.log("registered successfully");
}

function failed_reg(data){
    console.log("failed registration");
    if (data == 1) {
        document.getElementById("matching").innerHTML = "passwords do not match";
    }
}

function checkRegistration(data){
    data = JSON.parse(data);
    console.log(data);
    if (data["success"]){
        document.getElementById("matching").innerHTML = "";
        document.getElementById("emailTaken").innerHTML = "";
        document.getElementById("usernameTaken").innerHTML = "";
        login();
        window.location.href = "login.html";
    } else {
        if (data["email free"] == false){
            document.getElementById("emailTaken").innerHTML = "email taken"; 
        }
        if (data["username free"] == false){
            document.getElementById("usernameTaken").innerHTML = "username taken";
        }
    }
}
