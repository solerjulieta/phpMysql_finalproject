const btnpassword = document.getElementById('btnContrasena');
let tipo = document.getElementById('contrasena');
let iconOcultar = document.querySelector('.bi-eye-slash');
let iconMostrar = document.querySelector('.bi-eye');

iconMostrar.classList.add("visually-hidden");

btnContrasena.onclick = function(){
   if(tipo.type == 'password'){
      tipo.type = 'text';
      iconMostrar.classList.remove("visually-hidden")
      iconOcultar.classList.add("visually-hidden")
   } else {
      tipo.type = 'password';
      iconMostrar.classList.add("visually-hidden")
      iconOcultar.classList.remove("visually-hidden")
   }
}