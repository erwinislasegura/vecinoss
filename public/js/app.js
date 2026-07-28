document.querySelector('.menu-button')?.addEventListener('click', event => {
  const nav = document.querySelector('.main-nav');
  const open = nav.classList.toggle('open');
  event.currentTarget.setAttribute('aria-expanded', String(open));
});

const weatherWidget = document.querySelector('[data-weather-widget]');
if (weatherWidget) {
  const weatherCodes = { 0: ['Despejado', '☀'], 1: ['Mayormente despejado', '🌤'], 2: ['Parcialmente nublado', '⛅'], 3: ['Nublado', '☁'], 45: ['Niebla', '🌫'], 48: ['Niebla', '🌫'], 51: ['Llovizna', '🌦'], 53: ['Llovizna', '🌦'], 55: ['Llovizna intensa', '🌧'], 61: ['Lluvia', '🌧'], 63: ['Lluvia', '🌧'], 65: ['Lluvia intensa', '🌧'], 80: ['Chubascos', '🌦'], 81: ['Chubascos', '🌦'], 82: ['Chubascos fuertes', '⛈'], 95: ['Tormenta', '⛈'] };
  const showWeather = async (latitude, longitude, locationName) => {
    try {
      const endpoint = new URL('https://api.open-meteo.com/v1/forecast');
      endpoint.search = new URLSearchParams({ latitude, longitude, current: 'temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m', timezone: 'auto' });
      const response = await fetch(endpoint);
      if (!response.ok) throw new Error('Weather request failed');
      const { current } = await response.json();
      const condition = weatherCodes[current.weather_code] || ['Condiciones variables', '◌'];
      weatherWidget.querySelector('[data-weather-location]').textContent = locationName;
      weatherWidget.querySelector('[data-weather-temperature]').textContent = `${Math.round(current.temperature_2m)}°`;
      weatherWidget.querySelector('[data-weather-description]').textContent = condition[0];
      weatherWidget.querySelector('[data-weather-icon]').textContent = condition[1];
      weatherWidget.querySelector('[data-weather-apparent]').textContent = `${Math.round(current.apparent_temperature)}°`;
      weatherWidget.querySelector('[data-weather-humidity]').textContent = `${current.relative_humidity_2m}%`;
      weatherWidget.querySelector('[data-weather-wind]').textContent = `${Math.round(current.wind_speed_10m)} km/h`;
    } catch (error) { weatherWidget.querySelector('[data-weather-description]').textContent = 'No fue posible actualizar el tiempo'; }
  };
  const fallback = () => showWeather(weatherWidget.dataset.latitude, weatherWidget.dataset.longitude, weatherWidget.dataset.fallbackName);
  const showVisitorWeather = async ({ latitude, longitude }) => {
    const coordinates = `${Number(latitude).toFixed(2)}, ${Number(longitude).toFixed(2)}`;
    await showWeather(latitude, longitude, `Ubicación actual (${coordinates})`);
    try {
      const endpoint = new URL('https://api.bigdatacloud.net/data/reverse-geocode-client');
      endpoint.search = new URLSearchParams({ latitude, longitude, localityLanguage: 'es' });
      const response = await fetch(endpoint);
      if (!response.ok) throw new Error('Reverse geocoding request failed');
      const place = await response.json();
      const locality = place.locality || place.city || place.localityInfo?.administrative?.[3]?.name;
      const region = place.principalSubdivision;
      const locationName = [locality, region].filter((value, index, values) => value && values.indexOf(value) === index).join(', ');
      if (locationName) weatherWidget.querySelector('[data-weather-location]').textContent = locationName;
    } catch (error) {
      // Las coordenadas visibles siguen identificando el lugar si el geocodificador no responde.
    }
  };
  const locateVisitor = () => {
    if (!navigator.geolocation) return fallback();
    navigator.geolocation.getCurrentPosition(position => showVisitorWeather(position.coords), fallback, { enableHighAccuracy: false, timeout: 8000, maximumAge: 600000 });
  };
  weatherWidget.querySelector('[data-weather-locate]').addEventListener('click', locateVisitor);
  locateVisitor();
}

document.querySelector('[data-copy-url]')?.addEventListener('click', async event => {
  const button = event.currentTarget;
  try {
    await navigator.clipboard.writeText(button.dataset.copyUrl);
    const label = button.querySelector('b'); label.textContent = '¡Copiado!';
    setTimeout(() => { label.textContent = 'Copiar enlace'; }, 1800);
  } catch (error) { window.prompt('Copia el enlace de la noticia:', button.dataset.copyUrl); }
});

const videoDialog = document.querySelector('[data-video-dialog]');
if (videoDialog) {
  const player = videoDialog.querySelector('[data-video-player]');
  const embedUrl = value => {
    try {
      const url = new URL(value);
      const host = url.hostname.replace(/^www\./, '');
      let id = '';
      if (host === 'youtu.be') id = url.pathname.split('/')[1] || '';
      if (host === 'youtube.com' || host.endsWith('.youtube.com')) id = url.searchParams.get('v') || url.pathname.match(/^\/(?:shorts|embed)\/([^/]+)/)?.[1] || '';
      if (id && /^[a-zA-Z0-9_-]{6,}$/.test(id)) return `https://www.youtube-nocookie.com/embed/${id}?autoplay=1`;
      if (host === 'vimeo.com' || host.endsWith('.vimeo.com')) {
        id = url.pathname.match(/\/(?:video\/)?(\d+)/)?.[1] || '';
        if (id) return `https://player.vimeo.com/video/${id}?autoplay=1`;
      }
      if (host === 'dai.ly') id = url.pathname.split('/')[1] || '';
      if (host === 'dailymotion.com' || host.endsWith('.dailymotion.com')) id = url.pathname.match(/\/video\/([^_/?]+)/)?.[1] || '';
      if (id && /^[a-zA-Z0-9]+$/.test(id)) return `https://www.dailymotion.com/embed/video/${id}?autoplay=1`;
    } catch (error) { return null; }
    return null;
  };
  const playVideo = button => {
    const source = button.dataset.videoUrl;
    const embedded = embedUrl(source);
    const directVideo = /\.(?:mp4|webm|ogg)(?:[?#].*)?$/i.test(source);
    if (!embedded && !directVideo) {
      window.open(source, '_blank', 'noopener,noreferrer');
      return;
    }
    player.replaceChildren();
    const media = document.createElement(embedded ? 'iframe' : 'video');
    media.src = embedded || source;
    media.title = button.dataset.videoTitle;
    if (embedded) {
      media.allow = 'autoplay; fullscreen; picture-in-picture';
      media.allowFullscreen = true;
    } else {
      media.controls = true;
      media.autoplay = true;
    }
    player.append(media);
    videoDialog.querySelector('[data-video-dialog-title]').textContent = button.dataset.videoTitle;
    videoDialog.showModal();
  };
  document.querySelectorAll('[data-video-url]').forEach(button => button.addEventListener('click', () => playVideo(button)));
  const closeVideo = () => { videoDialog.close(); player.replaceChildren(); };
  videoDialog.querySelector('[data-video-close]').addEventListener('click', closeVideo);
  videoDialog.addEventListener('click', event => { if (event.target === videoDialog) closeVideo(); });
  videoDialog.addEventListener('close', () => player.replaceChildren());
}
