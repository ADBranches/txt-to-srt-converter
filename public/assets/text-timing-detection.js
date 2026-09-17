(() => {
    const area = document.querySelector('#lyrics');
    const summary = document.querySelector('#validation-summary');
    if (!area || !summary) return;

    const updateTimingState = () => {
        const active = area.value.split(/\r?\n/).filter((line) => line.trim());
        if (active.length === 0) return;
        const timestamped = /^\s*\d+:\d{2}:\d{2}[,.]\d{1,3}\s*\|/.test(active[0]);
        if (!timestamped) {
            summary.textContent = `Untimed text detected: ${active.length} non-empty lines. Preview is available for inspection, but final SRT conversion requires timestamps.`;
            summary.classList.remove('error');
            summary.classList.add('untimed');
        } else {
            summary.classList.remove('untimed');
        }
    };

    area.addEventListener('input', updateTimingState);
    updateTimingState();
})();
