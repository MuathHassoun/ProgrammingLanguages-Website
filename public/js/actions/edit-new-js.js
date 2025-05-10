function showEditSettings(blockId, languageId) {
  const frames = document.querySelectorAll('.language-form');
  frames.forEach(frame => frame.style.display = 'none');

  document.getElementById(blockId).style.display = 'block';
}
