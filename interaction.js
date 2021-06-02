    function update(){
        col = document.getElementById('posts');
        height = col.offsetHeight;
        verticalPosition = window.pageYOffset;
        bottom = verticalPosition + window.innerHeight;
       // console.log("bottom = " + bottom + " height= " + height + " verticalposition= " + verticalPosition + " innerheight= " + window.innerHeight);
        if(bottom >= height){
            $.ajax({
                url: "home_pagination.php",
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

    function share(id){
        $.ajax({
            url: "share.php",
            type: "post",
            data: {id: id},
            success: function(response){
               $("#sharescount_"+id).replaceWith(response);
            } 
        })
    }
    function like(tweetID){
        label = document.getElementById('count_' + tweetID);
        heart = document.getElementById('like-'+tweetID);
        $.ajax({
            url: "likes.php",
            type: "post",
            data: {id: tweetID},
            success:function(response){
                label.innerHTML = response.total_likes;
                if(response.like == 0){
                    heart.style.color = 'black';

                }
                else {
                    heart.style.color = 'crimson';
                }

            }
        })

    }
    function reply(id){
        window.location.href = "reply.php?id=" + id;
    }
    function newReply(tweetID){
        tweet = document.getElementById('newReply').value;
        replies = document.getElementById("replies");
        $.ajax({
            url: "reply2.php",
            type: "post",
            data: {tweet: tweet,
            tweetID: tweetID},
            success:function(response){
                replies.innerHTML += response;
            }
        })
    }
    function newTweet(){
        tweet = document.getElementById('newtweet').value;
        posts = document.getElementById('posts');
        $.ajax({
            url: "home2.php",
            type: "post",
            data: {tweet: tweet},
            success:function(response){
                response += posts.innerHTML;
                posts.innerHTML = response;
            }
        })
    }
    function follow(following, accountID, email, total){
        followers = document.getElementById('followers-count');
        $.ajax({
            url: "follow.php",
            type: "post",
            data: {following: following,
                    accountID: accountID,
                    email: email,
                    total: total},
            success:function(response){
                followers.innerHTML = response;
            }
        })
    }
    