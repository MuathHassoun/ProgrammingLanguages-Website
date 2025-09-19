document.getElementById('toggle-info-btn').addEventListener('click', function () {
  const target = document.getElementById('extra-info');
  if (target.style.display === 'none') {
    target.style.display = 'flex';
    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
  } else {
    target.style.display = 'none';
    this.innerHTML = '<i class="fas fa-eye"></i>';
  }
});

document.getElementById('edit-profile-btn').addEventListener('click', async function () {
  const fullName = document.querySelector('.profile-info h2')?.innerText || '';
  const username = document.querySelector('.profile-info p:nth-of-type(1)')?.innerText.replace('Username:', '').trim() || '';
  const email = document.querySelector('.profile-info p:nth-of-type(2)')?.innerText.replace('Email:', '').trim() || '';
  const shortIntro = document.querySelector('.profile-info p:nth-of-type(3)')?.innerText.replace('Short Introduction:', '').trim() || '';
  const longIntro = document.querySelector('.long-intro p')?.innerText.trim() || '';
  const currentPhotoSrc = document.getElementById('profile-photo')?.src || '';

  const formHtml = await fetch('../../php-pages/admin-side/edit_profile_form.html').then(res => res.text());
  if (!document.querySelector('link[href="../../css/edit_profile_modal.css"]')) {
    const style = document.createElement('link');
    style.rel = 'stylesheet';
    style.href = '../../css/edit_profile_modal.css';
    document.head.appendChild(style);
  }

  const { value: formValues } = await Swal.fire({
    title: 'Edit Profile',
    html: formHtml,
    width: '900px',
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: 'Save Changes',
    cancelButtonText: 'Cancel',
    background: '#1e293b',
    color: '#e2e8f0',
    confirmButtonColor: '#38bdf8',
    cancelButtonColor: '#475569',
    customClass: {
      popup: 'rounded-xl shadow-lg'
    },
    didOpen: () => {
      document.getElementById('swal-fullname').value = fullName;
      document.getElementById('swal-username').value = username;
      document.getElementById('swal-email').value = email;
      document.getElementById('swal-short').value = shortIntro;
      document.getElementById('swal-long').value = longIntro;

      const previewImg = document.getElementById('swal-photo-preview');
      previewImg.src = currentPhotoSrc;
      previewImg.addEventListener('click', function () {
        const imageUrl = this.src;
        if (!imageUrl) return;

        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = 0;
        overlay.style.left = 0;
        overlay.style.width = '100vw';
        overlay.style.height = '100vh';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.zIndex = 10000;
        overlay.style.cursor = 'pointer';

        const img = document.createElement('img');
        img.src = imageUrl;
        img.style.maxWidth = '80vw';
        img.style.maxHeight = '80vh';
        img.style.borderRadius = '12px';
        img.style.boxShadow = '0 0 20px rgba(0, 0, 0, 0.5)';
        img.alt = 'Full Size Preview';

        overlay.appendChild(img);
        document.body.appendChild(overlay);

        overlay.addEventListener('click', () => {
          overlay.remove();
        });
      });

      const fileInput = document.getElementById('swal-photo');
      fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];
        if (file && file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function (e) {
            previewImg.src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      });
    },
    preConfirm: () => {
      const fullname = document.getElementById('swal-fullname').value.trim();
      const email = document.getElementById('swal-email').value.trim();
      const short = document.getElementById('swal-short').value.trim();
      const long = document.getElementById('swal-long').value.trim();
      const photo = document.getElementById('swal-photo').files[0];

      if (!fullname || !email) {
        Swal.showValidationMessage('Full Name and Email are required.');
        return false;
      }

      const formData = new FormData();
      formData.append('full_name', fullname);
      formData.append('email', email);
      formData.append('short_self_introduction', short);
      formData.append('long_self_introduction', long);
      formData.append('username', username);

      if (photo) {
        formData.append('admin_photo', photo);
      }

      return fetch('../../php-pages/admin-side/edit_profile_handler.php', {
        method: 'POST',
        body: formData
      })
        .then(response => {
          if (!response.ok) throw new Error('Failed to update profile.');
          return response.text();
        })
        .catch(error => {
          Swal.showValidationMessage(`Error: ${error.message}`);
          return false;
        });
    }
  });

  if (formValues) {
    Swal.fire({
      icon: 'success',
      title: 'Profile Updated',
      text: 'Your profile has been successfully updated.',
      timer: 2000,
      showConfirmButton: false,
      background: '#1e293b',
      color: '#e2e8f0'
    }).then(() => location.reload());
  }
});
