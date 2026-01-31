let selectedBanner = null;

function openBannerModal() {
    document.getElementById('banner-modal').classList.add('active');
}

function closeBannerModal() {
    document.getElementById('banner-modal').classList.remove('active');
    selectedBanner = null;
    document.querySelectorAll('.banner-option').forEach(opt => opt.classList.remove('selected'));
}

function selectBanner(banner) {
    selectedBanner = banner;
    document.querySelectorAll('.banner-option').forEach(opt => opt.classList.remove('selected'));
    document.querySelector(`[data-banner="${banner}"]`).classList.add('selected');
}

function saveBanner() {
    if(!selectedBanner) {
        alert('Please select a banner first!');
        return;
    }

    fetch('actions/save_banner.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'banner=' + selectedBanner
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert('Failed to save banner');
        }
    });
}