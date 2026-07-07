class RadioPlayer {
    constructor() {
        this.audio = new Audio();
        this.stations = [];
        this.currentStation = null;
        this.isPlaying = false;
        this.volume = parseFloat(localStorage.getItem('radio-volume') ?? '0.8');

        this.audio.volume = this.volume;

        this.audio.addEventListener('error', () => this.handleError());
        this.audio.addEventListener('play', () => this.onPlay());
        this.audio.addEventListener('pause', () => this.onPause());

        this.init();
    }

    async init() {
        await this.fetchStations();
        this.renderStationList();
        this.restoreSession();
    }

    async fetchStations() {
        try {
            const res = await axios.get('/api/v1/stations');
            this.stations = res.data.data ?? [];
        } catch {
            this.stations = [];
        }
    }

    renderStationList() {
        const container = document.getElementById('station-list');
        if (!container) return;

        if (this.stations.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-16 text-[#706f6c] dark:text-[#A1A09A]">
                    <p class="text-lg">No radio stations available yet.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = this.stations.map(s => `
            <button
                type="button"
                class="station-card group relative flex flex-col items-center text-center p-6 rounded-xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#f53003] dark:hover:border-[#FF4433] transition-all duration-200 cursor-pointer ${this.currentStation?.id === s.id ? 'ring-2 ring-[#f53003] dark:ring-[#FF4433]' : ''}"
                data-station-id="${s.id}"
            >
                <div class="w-20 h-20 rounded-full overflow-hidden bg-[#f5f5f5] dark:bg-[#1a1a1a] mb-4 flex items-center justify-center ring-1 ring-black/5 dark:ring-white/10">
                    ${s.logo_url
                        ? `<img src="${s.logo_url}" alt="${s.name}" class="w-full h-full object-cover">`
                        : `<svg class="w-8 h-8 text-[#706f6c] dark:text-[#A1A09A]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" /></svg>`
                    }
                </div>
                <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] text-sm mb-1">${s.name}</h3>
                ${s.tagline ? `<p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-1">${s.tagline}</p>` : ''}
                <div class="flex items-center gap-2 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    ${s.frequency ? `<span>${s.frequency}</span>` : ''}
                    ${s.frequency && s.genre ? `<span>·</span>` : ''}
                    ${s.genre ? `<span>${s.genre}</span>` : ''}
                </div>
            </button>
        `).join('');

        container.querySelectorAll('.station-card').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.stationId);
                const station = this.stations.find(s => s.id === id);
                if (station) this.play(station);
            });
        });
    }

    play(station) {
        const stream = station.default_stream;
        if (!stream) return;

        this.currentStation = station;
        this.audio.src = stream.stream_url;
        this.audio.play().catch(() => {});
        this.isPlaying = true;
        this.updateUI();
        this.saveSession();
    }

    togglePlay() {
        if (this.currentStation) {
            if (this.isPlaying) {
                this.audio.pause();
            } else {
                this.audio.play().catch(() => {});
            }
        } else if (this.stations.length > 0) {
            this.play(this.stations[0]);
        }
    }

    stop() {
        this.audio.pause();
        this.audio.src = '';
        this.isPlaying = false;
        this.currentStation = null;
        this.updateUI();
        this.saveSession();
    }

    setVolume(val) {
        this.volume = Math.max(0, Math.min(1, val));
        this.audio.volume = this.volume;
        localStorage.setItem('radio-volume', this.volume);
        const volFill = document.getElementById('volume-fill');
        if (volFill) volFill.style.width = `${this.volume * 100}%`;
    }

    onPlay() {
        this.isPlaying = true;
        this.updateUI();
    }

    onPause() {
        this.isPlaying = false;
        this.updateUI();
    }

    handleError() {
        this.isPlaying = false;
        this.updateUI();
    }

    updateUI() {
        const nowPlaying = document.getElementById('now-playing');
        const playBtn = document.getElementById('play-btn');
        const playIcon = document.getElementById('play-icon');
        const pauseIcon = document.getElementById('pause-icon');
        const playerBar = document.getElementById('player-bar');

        if (!this.currentStation || !this.isPlaying) {
            if (nowPlaying) {
                if (this.currentStation) {
                    nowPlaying.innerHTML = `
                        <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Paused</span>
                        <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">${this.currentStation.name}</span>
                    `;
                } else {
                    nowPlaying.innerHTML = '<span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Select a station</span>';
                }
            }
            if (playBtn) playBtn.classList.remove('playing');
            if (playIcon) playIcon.classList.remove('hidden');
            if (pauseIcon) pauseIcon.classList.add('hidden');
        } else {
            if (nowPlaying) {
                nowPlaying.innerHTML = `
                    <span class="flex items-center gap-1.5 text-xs text-[#f53003] dark:text-[#FF4433]">
                        <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                        LIVE
                    </span>
                    <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">${this.currentStation.name}</span>
                `;
            }
            if (playBtn) playBtn.classList.add('playing');
            if (playIcon) playIcon.classList.add('hidden');
            if (pauseIcon) pauseIcon.classList.remove('hidden');
        }

        this.renderStationList();
    }

    saveSession() {
        if (this.currentStation) {
            localStorage.setItem('radio-station-id', this.currentStation.id);
        } else {
            localStorage.removeItem('radio-station-id');
        }
    }

    restoreSession() {
        const savedId = localStorage.getItem('radio-station-id');
        if (savedId && this.stations.length > 0) {
            const station = this.stations.find(s => s.id === parseInt(savedId));
            if (station) {
                this.currentStation = station;
                this.updateUI();
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.radioPlayer = new RadioPlayer();
});
