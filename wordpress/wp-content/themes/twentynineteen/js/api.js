//alert('working');
var job_submit = document.getElementById('job_submit');

if( job_submit ) {
    job_submit.addEventListener("click", function(){
      //  alert('clicked');
        var ourPostData = {
            "job_title" : document.querySelector('.admin-quick-add [name="job_title"]').value,
            "job_desc" : document.querySelector('.admin-quick-add [name="job_desc"]').value,
            "status": "publish"
        }

        var createPost = new XMLHttpRequest();

        createPost.open('job', 'http://localhost/wordpress/wp-json/wp/v2/job');
        createPost.setRequestHeader('X-WP-Nonce', additionalData.nonce);
        createPost.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        createPost.send(JSON.stringify(ourPostData));
        createPost.onreadystatechange = function() {
            if(createPost.readyState == 4) {
                if( createPost.status == 201 ) {
                    document.querySelector('.admin-quick-add [name="job_title"]').value = '';
                    document.querySelector('.admin-quick-add [name="job_desc"]').value = '';
                } else {
                    alert('Error - Try again.');
                }
            }
        }

    });
}