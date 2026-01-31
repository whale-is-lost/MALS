
function applyFilter(type, value) {
    const url = new URL(window.location);
    url.searchParams.set(type, value);
    window.location.href = url.toString();
}

function clearFilters() {
    window.location.href = 'index.php';
}
