(function () {
    var progress = document.getElementById('watch-progress');
    var cta = document.getElementById('delayed-cta');
    if (!progress || !cta) {
        return;
    }

    var seconds = 0;
    var delay = 8;
    var timer = window.setInterval(function () {
        seconds += 1;
        progress.style.width = Math.min(100, (seconds / delay) * 100) + '%';
        if (seconds >= delay) {
            cta.classList.remove('hidden');
            window.clearInterval(timer);
        }
    }, 1000);
}());

