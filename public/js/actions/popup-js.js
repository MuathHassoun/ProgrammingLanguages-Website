document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('notification-btn');
  const popup = document.getElementById('notification-popup');

  btn.addEventListener('click', function (e) {
    e.preventDefault();
    popup.classList.toggle('hidden');

    const username = this.dataset.username;
    fetch('../../php-pages/server-side/set_unseen_msg.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `set-unseen-username=${encodeURIComponent(username)}`
    })
      .then(() => {
        const badgeElements = document.getElementsByClassName('badge');
        for (let badge of badgeElements) {
          badge.style.display = 'none';
          badge.classList.add('no-msg-badge');
        }
      })
      .catch(() => {});
    e.stopPropagation();
  });

  document.addEventListener('click', function () {
    if (!popup.classList.contains('hidden')) {
      popup.classList.add('hidden');
    }
  });

  popup.addEventListener('click', function (e) {
    e.stopPropagation();
  });
});
