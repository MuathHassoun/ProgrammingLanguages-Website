window.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  if (params.get("scroll") === "true") {
    setTimeout(() => {
      window.scrollTo({ top: 20, behavior: "smooth" });
    }, 300);
  }
});

document.querySelectorAll('.card-container').forEach(container => {
  const arrow = container.querySelector('.toggle-arrow');
  const card = container.querySelector('.inner-card');

  arrow.addEventListener('click', () => {
    card.classList.toggle('flipped');
  });
});
