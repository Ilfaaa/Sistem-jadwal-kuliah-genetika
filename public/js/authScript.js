// ============================
// SweetAlert — error messages
// ============================

// Register errors
let errorRegister = document.querySelectorAll(".errorRegister");
if (errorRegister.length > 0) {
  // Show register panel (handled by inline script in blade, keep here as fallback)
  let errorText = '';
  for (let i = 0; i < errorRegister.length; i++) {
    errorText += `${errorRegister[i].value}<br>`;
  }
  Swal.fire({
    position: 'top',
    icon: 'warning',
    title: 'Data Register Invalid!',
    confirmButtonText: '<i class="fa fa-thumbs-up"></i> OKE!',
    html: `<p style="color:#e83e3e">${errorText}</p>`
  });
}

// Login errors
let errorLogin = document.querySelectorAll(".errorLogin");
if (errorLogin.length > 0) {
  let errorText = '';
  for (let i = 0; i < errorLogin.length; i++) {
    errorText += `${errorLogin[i].value}<br>`;
  }
  Swal.fire({
    position: 'top',
    icon: 'warning',
    title: 'Data Login Invalid!',
    confirmButtonText: '<i class="fa fa-thumbs-up"></i> OKE!',
    html: `<p style="color:#e83e3e">${errorText}</p>`
  });
}

// General success message (e.g. after register)
const message = document.querySelector(".theMessage");
if (message) {
  Swal.fire({
    position: 'top',
    icon: 'success',
    title: message.value,
    confirmButtonText: '<i class="fa fa-thumbs-up"></i> OKE!',
  });
}

// Login error message
const messageLogin = document.querySelector(".theMessageLogin");
if (messageLogin) {
  Swal.fire({
    icon: 'error',
    title: messageLogin.value
  });
}
