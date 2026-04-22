// Capture every click on buttons and links
document.addEventListener('click', function(e) {
    let target = e.target.closest('a, button');
    if (target) {
        let text = target.innerText.trim() || target.getAttribute('title') || "Action Button";
        
        // Send to background logger
        fetch('../api/ui_logger.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=' + encodeURIComponent('UI_INTERACTION: Pressed [' + text + ']')
        });
    }
});