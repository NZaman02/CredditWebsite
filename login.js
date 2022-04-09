$(document).on("submit", "#loginForm", function(e){
    //e.preventDefault();
    $.ajax({
        method:"POST",
        url: "createToken.php",
        data: $(this).serialize(),
        success: function(data){
            login(data);
        }
    });

});

function login(data){
    if (data.charAt(0) != "<"){
        // local storage is less secure than cookies for jwt
        localStorage.setItem("JWT", data);
        console.log("stored token");
        console.log(data);
    //window.location.replace("feedpage.html");
        alert("Logged In")

    } else {
        console.log("failed login");
        alert("Login failed");

    }
};


