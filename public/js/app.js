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
  const locateVisitor = () => {
    if (!navigator.geolocation) return fallback();
    navigator.geolocation.getCurrentPosition(position => showWeather(position.coords.latitude, position.coords.longitude, 'Tu ubicación actual'), fallback, { enableHighAccuracy: false, timeout: 8000, maximumAge: 600000 });
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
