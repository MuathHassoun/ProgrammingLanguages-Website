window.showLanguage = showLanguage;
function showLanguage(languageId) {
  const frames = document.querySelectorAll('.language-frame');
  frames.forEach(frame => frame.style.display = 'none');
  document.getElementById(languageId).style.display = 'block';
}
