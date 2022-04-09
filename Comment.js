var baseIndex = 0;
const increment = 5;

function setSelectedCommentId(comment){
    var contentDiv = comment.children[0];
    contentDiv.style.backgroundColor = "#a8a8a8";
    selectedComment = comment.getAttribute("data-id");
    formId = comment.getAttribute("data-formClass");
    form = document.getElementsByClassName(formId)[0];
    form.children[0].value = 0;
    form.children[2].value = selectedComment;
}

function loadData(){
    var formData = {
        'baseInd': baseIndex,
        'endInd': baseIndex + increment
    }
    $.ajax({
        method: "GET",
        url: "loadData.php",
        data: formData,
        success: function(data){
            console.log("Success");
            console.log(data)
            baseIndex += 5;
            populatePosts(data);
        },
        failure: function(data){ console.log(data); } 
    });
};

function toggleSubComments(div){
    console.log(div.style.display);
    div.classList.toggle("show");
    div.classList.toggle("hide");
}

function populateComments(comments, parentDiv, postId, formClass, parentId){
    for (let i = 0; i < comments.length; i++){
        var commentDiv = document.createElement("div");
        commentDiv.class = "comment";
        commentDiv.setAttribute("data-id", comments[i][0][0]);
        commentDiv.setAttribute("data-parentId", parentId);
        commentDiv.setAttribute("data-postId", postId);
        commentDiv.setAttribute("data-formClass", formClass);
        var contentDiv = document.createElement("div");
        contentDiv.setAttribute("class", "commentContent");
        var subDiv = Object.assign(document.createElement("div"),{className:"commentDropdown"});
        if (parentId == undefined){
            subDiv.classList.add("show");
        } else {
            subDiv.classList.add("hide");
        }
        contentDiv.innerHTML = comments[i][0][2];
        parentDiv.appendChild(commentDiv);
        commentDiv.appendChild(contentDiv);
        commentDiv.appendChild(subDiv);
        commentDiv.onclick = function(e){
            if (e.target == this.children[0]){
                toggleSubComments(this.children[1]);
                setSelectedCommentId(this);
            }
        };
        populateComments(comments[i][1], subDiv, postId, formClass, comments[i][0][0]);

    }
}

function populatePosts(data){
    data = JSON.parse(data);
    createPosts(data);
    console.log(data[0]);
    console.log(data[1])
}

function setColour(div, color){
    div.style.backgroundColor = color;
    for (let i = 0; i < div.children.length; i++){
        setColour(div.children[i], color);
    }
}

function clickCommentSection(commentSection){
    setColour(commentSection, "white");
}

function populatePost(id, data, postId){
    var domElement = document.getElementById(id);
    $("#" + id + " h1").html(data["preview"]["title"]);
    $("#" + id + " img").attr("src", data["preview"]["cover"]);
    $("#" + id).attr("data-id", data["id"]);
    $("#parentId", "#" + id).attr("value", data["id"]);
    $("#postId", "#" + id).attr("value", data["id"]);
    populateComments(data["comments"], domElement.children[4], data["id"], domElement.children[4].getAttribute("data-id"));
}

function createVoteForm(upvote, postId){
    if (upvote){
        var content = "upvote";
    } else {
        var content = "downvote";
    }
    var form = document.createElement("form");
    form.method = "POST";
    var vote = document.createElement("input");
    vote.name = "vote";
    vote.type = "hidden";
    //vote.value = upvote;
    if (content == "upvote"){
        vote.value = true;
    } 
    if (content == "false"){
        vote.value = false;
    }
    
    var postIdInp = document.createElement("input");
    postIdInp.value = postId;
    postIdInp.name = "postId";
    postIdInp.type = "hidden";
    var submit = document.createElement("input");
    submit.class = "Voting";
    submit.type = "submit";
    submit.value = content;
    submit.id = "submit";
    form.id = "vote";
    form.appendChild(vote);
    form.appendChild(postIdInp);
    form.appendChild(submit);
    form.classList.add("voteForm")
    return form;
}

function generatePostDiv(data,index){
    var title = data["preview"]["title"];
    var source = data["preview"]["cover"];
    //url
    var url = data["url"];
    var articleId = data["id"];
    var postId = "article" + articleId;
    var formClass = "form" + articleId;
    var post = Object.assign(document.createElement("div"),{className:"article", id:"postId"});
    var header = Object.assign(document.createElement("h1"), {innerText:title});
    //url 
    var a = document.createElement("a");
    var link = document.createTextNode("Link");
    a.appendChild(link);
    a.title="Link";
    a.href = url;
    
    var TheUrl = Object.assign(a);

    var info = Object.assign(document.createElement("div"), {className:"postInfo"});
    info.appendChild(createVoteForm(true, articleId));
    info.appendChild(createVoteForm(false, articleId));
    info.appendChild(Object.assign(document.createElement("div"), {innerHTML:data["credValue"]}));
    var imDiv =  document.createElement("div");
    imDiv.appendChild(Object.assign(document.createElement("img"), {src:source, width:"500em", height:"500em"}));
    var form = Object.assign(document.createElement('form'),{id:"comment_form", className:formClass, method:"POST"});
    form.setAttribute("data-id", ("article"+postId));
    form.appendChild(Object.assign(document.createElement('input'),{type:"hidden", id:"isBase", name:"isBase", value:1}))
    form.appendChild(Object.assign(document.createElement('input'),{type:"hidden", id:"postId", name:"postId", value:articleId}));
    form.appendChild(Object.assign(document.createElement('input'),{type:"hidden", id:"parentId", name:"parentId", value:"None"}));
    form.appendChild(Object.assign(document.createElement('input'),{type:"text", id:"comment", name:"comment", value:"test" }));
    form.appendChild(Object.assign(document.createElement('input'),{type:"submit", id:"submit", name:"submit", value:"submit"}));
    comments = Object.assign(document.createElement("div"), {id:"comments", className:"commentScroll"});
    comments.onclick = function(){
        clickCommentSection(this);
    };
    post.appendChild(header);
    //url
    post.appendChild(TheUrl);
    post.appendChild(info);
    post.appendChild(imDiv);
    post.appendChild(form);
    post.appendChild(comments);
    var feed = document.getElementById("feed");
    feed.appendChild(post);
    populateComments(data["comments"], comments, data["id"], formClass);
}

function SetCommentListener(){
    $(document).on('submit', '#comment_form', function(e){
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: "comment.php",
            headers:{
                "Authorization": localStorage.getItem("JWT")
            },
            data: $(this).serialize(),
            success: function(data){
                console.log(data);
            },
            failure: function(data){
                console.log(data);
            }
        });
    });
}

function SetVoteListener(){
    $(document).on('submit', '#vote', function(e){
        e.preventDefault(e);
        console.log($(this).serialize());
        $.ajax({
            method: "POST",
            url: "vote.php",
            headers:{
                "Authorization": localStorage.getItem("JWT")
            },
            data: $(this).serialize(),
            success: function(data){
                console.log(data);
            },
            failure: function(data){
                console.log(data);
            }
        });
    });
}

function createPosts(data){
    for (let i = 0; i < data.length; i ++){
        generatePostDiv(data[i],i);
    }
    SetCommentListener();
    SetVoteListener();
}



$(document).on('submit', '#loadPosts', function(e){
    e.preventDefault(e);
    loadData();
});

window.onload(loadData());
