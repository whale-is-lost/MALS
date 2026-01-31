
(function() {
    const searchInput = document.getElementById('userSearch');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.classList.remove('show');
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchUsers(query);
        }, 300);
    });
    
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('show');
        }
    });
    
    function searchUsers(query) {
        fetch(`actions/search-users.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.innerHTML = '<div class="no-results">Search error</div>';
                searchResults.classList.add('show');
            });
    }
    
    function displayResults(users) {
        if (users.length === 0) {
            searchResults.innerHTML = '<div class="no-results">No users found</div>';
            searchResults.classList.add('show');
            return;
        }
        
        const currentUserId = searchInput.dataset.currentUserId;
        console.log('Current User ID:', currentUserId, typeof currentUserId);
        
        let html = '';
        users.forEach(user => {
            console.log('User ID:', user.id, typeof user.id, 'Match:', String(user.id) === String(currentUserId));
            const isCurrentUser = currentUserId && String(user.id) === String(currentUserId);
            const redirectUrl = isCurrentUser ? 'profile_page.php' : `user_profile.php?id=${user.id}`;
            const avatarPath = `images/avatars/${user.avatar}`;
            
            html += `
                <div class="search-result-item" onclick="window.location.href='${redirectUrl}'">
                    <img src="${avatarPath}" alt="${escapeHtml(user.username)}" class="user-avatar">
                    <span>${escapeHtml(user.username)}</span>
                </div>
            `;
        });
        
        searchResults.innerHTML = html;
        searchResults.classList.add('show');
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
})();
