// Swal.fire({
//   title: 'Are you sure?',
//   text: "You won't be able to revert this!",
//   icon: 'warning',
//   showCancelButton: true,
//   confirmButtonColor: '#3085d6',
//   cancelButtonColor: '#d33',
//   confirmButtonText: 'Yes, delete it!'
// }).then((result) => {
//   if (result.isConfirmed) {
//     Swal.fire(
//       'Deleted!',
//       'Your file has been deleted.',
//       'success'
//     )
//   }
// })
const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

let errorRegister = document.querySelectorAll(".errorRegister")

if (errorRegister.length > 0) {
  container.classList.add("sign-up-mode");

  let errorText = ''
  for (let i = 0; i < errorRegister.length; i++){
    errorText += `${errorRegister[i].value}<br>`
  }

  Swal.fire({
    position: 'top',
    icon: 'warning',
    title: 'Data Register Invalid!',
    confirmButtonText:
    '<i class="fa fa-thumbs-up"></i> OKE!',
    html: `<p class="redColor">${errorText}</p>`
  })
}

let errorLogin = document.querySelectorAll(".errorLogin")

if (errorLogin.length > 0) {
  container.classList.remove("sign-up-mode");

  let errorText = ''
  for (let i = 0; i < errorLogin.length; i++){
    errorText += `${errorLogin[i].value}<br>`
  }

  Swal.fire({
    position: 'top',
    icon: 'warning',
    title: 'Data Login Invalid!',
    confirmButtonText:
    '<i class="fa fa-thumbs-up"></i> OKE!',
    html: `<p class="redColor">${errorText}</p>`
  })
}

const message = document.querySelector(".theMessage");
if (message) {
  Swal.fire({
    position: 'top',
    icon: 'success',
    title: message.value,
    confirmButtonText:
    '<i class="fa fa-thumbs-up"></i> OKE!',
  })
}

const messageLogin = document.querySelector(".theMessageLogin");
if (messageLogin) {
  Swal.fire({
    icon: 'error',
    title: messageLogin.value
  })
}

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});
