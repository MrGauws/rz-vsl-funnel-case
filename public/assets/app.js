(function () {
    var progress = document.getElementById('watch-progress');
    var cta = document.getElementById('delayed-cta');
    if (progress && cta) {
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
    }

    var mount = document.getElementById('funnel-meter');
    if (mount && window.Vue) {
        window.Vue.createApp({
            data: function () {
                return {
                    variant: mount.dataset.variant || 'A',
                    signals: [
                        { label: 'Video', value: 'ready' },
                        { label: 'Attribution', value: 'preserved' },
                        { label: 'Webhook', value: 'idempotent' }
                    ]
                };
            },
            template: [
                '<div class="meter-inner">',
                '  <span class="meter-label">Vue launch signals</span>',
                '  <span v-for="signal in signals" :key="signal.label">',
                '    <strong>{{ signal.label }}</strong> {{ signal.value }}',
                '  </span>',
                '  <span><strong>Variant</strong> {{ variant }}</span>',
                '</div>'
            ].join('')
        }).mount(mount);
    } else if (mount) {
        mount.innerHTML = '<div class="meter-inner"><span class="meter-label">Launch signals</span><span><strong>Video</strong> ready</span><span><strong>Attribution</strong> preserved</span><span><strong>Webhook</strong> idempotent</span></div>';
    }
}());
