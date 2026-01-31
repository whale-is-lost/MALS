let currentAvatarIndex = 0;
const avatarImages = document.querySelectorAll('.avatar-image');
const totalAvatars = avatarImages.length;

function findCurrentAvatar() {
    avatarImages.forEach((img, index) => {
        if(img.classList.contains('active')) {
            currentAvatarIndex = index;
        }
    });
}

findCurrentAvatar();

function nextAvatar() {
    const currentImg = avatarImages[currentAvatarIndex];
    currentImg.classList.remove('active');
    currentImg.classList.add('fade-out-left');
    
    setTimeout(() => {
        currentImg.classList.remove('fade-out-left');
        currentAvatarIndex = (currentAvatarIndex + 1) % totalAvatars;
        
        const nextImg = avatarImages[currentAvatarIndex];
        nextImg.classList.add('fade-in-right');
        nextImg.classList.add('active');
        
        setTimeout(() => {
            nextImg.classList.remove('fade-in-right');
        }, 300);
    }, 150);
}

function previousAvatar() {
    const currentImg = avatarImages[currentAvatarIndex];
    currentImg.classList.remove('active');
    currentImg.classList.add('fade-out-right');
    
    setTimeout(() => {
        currentImg.classList.remove('fade-out-right');
        currentAvatarIndex = (currentAvatarIndex - 1 + totalAvatars) % totalAvatars;
        
        const prevImg = avatarImages[currentAvatarIndex];
        prevImg.classList.add('fade-in-left');
        prevImg.classList.add('active');
        
        setTimeout(() => {
            prevImg.classList.remove('fade-in-left');
        }, 300);
    }, 150);
}

function saveAvatar() {
    const selectedAvatar = avatarImages[currentAvatarIndex].getAttribute('data-avatar');
    
    fetch('actions/save_avatar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'avatar=' + selectedAvatar
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Avatar saved! Refresh to see changes.');
            location.reload();
        } else {
            alert('Failed to save avatar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}