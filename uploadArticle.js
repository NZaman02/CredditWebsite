$(document).on("submit", "#submitArticle", function(e){
    e.preventDefault();
    $.ajax({
        method: "POST",
        url: "uploadArticle.php",
        headers:{
            "Authorization": localStorage.getItem("JWT")
        },
        data: $(this).serialize(),
        success: function(data){
            console.log(data);
        }
    });
    disableButton();
});

function disableButton() {
  document.getElementById("submit").disabled = true;
  alert("done");
}
