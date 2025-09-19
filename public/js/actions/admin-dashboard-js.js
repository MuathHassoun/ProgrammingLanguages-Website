document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.tab-button');
  const contents = document.querySelectorAll('.tab-content');
  buttons.forEach(button => {
    button.addEventListener('click', () => {
      buttons.forEach(btn => btn.classList.remove('active'));
      contents.forEach(content => content.classList.remove('active'));

      button.classList.add('active');
      const tabId = button.getAttribute('data-tab');
      document.getElementById(tabId).classList.add('active');
    });
  });
});

// Delete user
document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('.delete-btn');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const username = this.dataset.username;
      fetch('../../php-pages/admin-side/delete_user.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `delete-username=${encodeURIComponent(username)}&role=user`
      })
      .then(response => response.text())
      .then(() => {
        this.closest('tr').remove();
      })
      .catch(error => {
        showAlert(
          'error',
          'Deletion Failed',
          'An error occurred while trying to delete the user. You will be redirected to the homepage.\n' + error.message,
          '#d33'
        );
      });
    });
  });
});

// Delete admin
document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('.delete-admin-btn');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const active = this.dataset.active;
      const username = this.dataset.username;
      if (!(active === 'admin-muath29')) {
        showAlert(
          'error',
          'Deletion Failed',
          'You do not have permission to delete other admin accounts. Only the founder has this privilege.',
          '#d33'
        );
        return;
      }

      fetch('../../php-pages/admin-side/delete_user.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `delete-username=${encodeURIComponent(username)}&role=admin`
      })
        .then(response => response.text())
        .then(() => {
          this.closest('tr').remove();
        })
        .catch(error => {
          showAlert(
            'error',
            'Deletion Failed',
            'An error occurred while trying to delete the user. You will be redirected to the homepage.\n' + error.message,
            '#d33'
          );
        });
    });
  });
});

// Delete a contact message or reply
document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('.delete-message-btn');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      fetch('../../php-pages/admin-side/delete_contact_message.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `delete-contact-id=${encodeURIComponent(id)}`
      })
        .then(response => response.text())
        .then(() => {
          this.closest('.message-item').remove();
        })
        .catch(error => {
          showAlert(
            'error',
            'Deletion Failed',
            'An error occurred while trying to delete the contact msg. You will be redirected to the homepage.\n' + error.message,
            '#d33'
          );
        });
    })
  })

  const replyButtons = document.querySelectorAll('.reply-message-btn');
  replyButtons.forEach(button => {
    button.addEventListener('click', function () {
      const messageId = this.dataset.messageid;
      const name = this.dataset.name;
      const to_username = this.dataset.username;
      const subject = this.dataset.subject;

      Swal.fire({
        title: 'Reply to Message',
        input: 'textarea',
        inputLabel: 'Your reply',
        inputPlaceholder: 'Type your message here...',
        inputAttributes: {
          'aria-label': 'Type your message here'
        },
        showCancelButton: true,
        confirmButtonText: 'Send Reply',
        cancelButtonText: 'Cancel',
        background: '#1e293b',
        color: '#e2e8f0',
        confirmButtonColor: '#38bdf8',
        cancelButtonColor: '#475569',
        customClass: {
          title: 'swal2-title-custom',
          input: 'swal2-input-custom'
        },
        preConfirm: (replyText) => {
          if (!replyText) {
            Swal.showValidationMessage('Reply cannot be empty');
            return false;
          }

          return fetch('../../php-pages/admin-side/reply_message.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body:
              `reply-message-id=${encodeURIComponent(messageId)}` +
              `&reply-name=${encodeURIComponent(name)}` +
              `&reply-to_username=${encodeURIComponent(to_username)}` +
              `&reply-subject=${encodeURIComponent(subject)}` +
              `&reply-content=${encodeURIComponent(replyText)}`
          })
            .then(response => {
              if (!response.ok) throw new Error('Network response was not ok');
              return response.text();
            })
            .catch(error => {
              Swal.showValidationMessage(`Request failed: ${error.message}`);
              return false;
            });
        }
      }).then(result => {
        if (result.isConfirmed) {
          Swal.fire({
            icon: 'success',
            title: '✅ Replied!',
            text: 'Your reply has been sent.',
            background: '#1e293b',
            color: '#e2e8f0',
            confirmButtonColor: '#38bdf8'
          });
        }
      });
    });
  })
});
