function update(){
    col = document.getElementById('col');
    height = col.offsetHeight;
    verticalPosition = window.pageYOffset;
    bottom = verticalPosition + window.innerHeight;
    if(bottom >= height){
        $.ajax({
            url: "profile_pagination.php",
            type: "post",
            success:function(response){
                col.innerHTML += response;
            }
        })
    }
}

window.onscroll = update;
function search(){
 search = document.getElementById('search').value;
 searchResults = document.getElementById('search-results');
 $.ajax({
     url: "search.php",
     type: "get",
     data: {search: search},
     success:function(response){
         searchResults.innerHTML = response;
     }
 })
}
