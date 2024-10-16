// Slide in and out the success alert
document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('success-alert');
    if (alert) {
        // Slide in
        setTimeout(() => {
            alert.classList.remove('-translate-y-full', 'opacity-0');
        }, 100); // Delay to ensure transition

        // Slide out after 3 seconds
        setTimeout(() => {
            alert.classList.add('-translate-y-full', 'opacity-0');
        }, 4000); // Display for 3 seconds before sliding out
    }
});