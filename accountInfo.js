// window.onload = function (){
//     $.ajax({
//         method: "GET",
//         url: "getCredentials.php",
//         headers: { 
//             "Authorization": localStorage.getItem("JWT")
//         },
//         success: function(data){
//             fillParameters(data)
//         }
//     });
// };

function fillParameters(data){
    data = JSON.parse(data);
    console.log(data);
    document.getElementById("emailInp").value = data["email"];
    document.getElementById("usernameInp").value = data["username"];
};

function addSubmitListener(){

$(document).on("submit", "#changeEmail", function(e){
    e.preventDefault();
    $.ajax({
        method:"POST",
        url: "changeEmail.php",
        headers:{
            "Authorization": localStorage.getItem("JWT"),
        },
        data: $(this).serialize(),
        success: function(data){
            console.log(data);
        }
    });
});

$(document).on("submit", "#changeUsername", function(e){
    e.preventDefault();
    $.ajax({
        method:"POST",
        url: "changeUsername.php",
        headers:{
            "Authorization": localStorage.getItem("JWT"),
        },
        data: $(this).serialize(),
        success: function(data){
            console.log(data);
        }
    });
});


    $(document).on("submit", "#changePassword", function(e){
        e.preventDefault();
        password1 = $("#password1").val();
        password2 = $("#password2").val();
        if (password1 === password2){
        $.ajax({
            method:"POST",
            url: "changePassword.php",
            headers:{
                "Authorization": localStorage.getItem("JWT"),
            },
            data: $(this).serialize(),
            success: function(data){
                console.log(data);
            }
        });
    } else {
        console.log("passwords dont match");
    }
    });
};