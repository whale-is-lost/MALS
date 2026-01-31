function toggleLike(postId, button) {
    fetch('actions/toggle_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + postId
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const likeImg = button.querySelector('.like-icon');
            const likeCount = button.querySelector('.like-count');
            
            if(data.liked) {
                likeImg.src = 'images/piggy_front.gif';
                button.classList.add('liked');
            } else {
                likeImg.src = 'images/piggy_back.gif'; 
                button.classList.remove('liked');
            }
            
            likeCount.textContent = data.count;
            
            button.style.transform = 'scale(1.2)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 200);
        } else {
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}