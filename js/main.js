const d = document; 
const modal = d.querySelector('.ventana_modal');
const eliminar = d.querySelectorAll('.eliminar');
const nombreProducto = d.getElementById('nom-prod');
const idProducto = d.getElementById('id-prod');
const formEliminar = d.querySelectorAll('.form-eliminar');

eliminar.forEach(btn => {
   btn.addEventListener('click', function(ev){
      const nombre = this.dataset.name;
      const id = this.dataset.id;
      idProducto.value = id;
      nombreProducto.innerHTML = `${nombre}`;
      modal.classList.add('modal--ver');;
    });
})

let cerrar = d.getElementById('cerrar');

cerrar.onclick = function (){
   modal.classList.remove('modal--ver');
}