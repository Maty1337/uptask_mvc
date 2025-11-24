// Seleccionar elementos
const sidebar = document.querySelector('.sidebar');
const mobileMenuBtn = document.querySelector('#mobile-menu'); 
const cerrarMenuBtn = document.querySelector('#cerrar-menu');

// Abrir menú
if(mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', function() {
        sidebar.classList.add('mostrar');
    });
}

// Cerrar menú
if(cerrarMenuBtn) {
    cerrarMenuBtn.addEventListener('click', function(){
        sidebar.classList.add('ocultar');
        setTimeout(() => {
            sidebar.classList.remove('mostrar');
            sidebar.classList.remove('ocultar');
        }, 300); // <-- Agregar tiempo en ms
    });
}

// Cerrar menú al redimensionar
window.addEventListener('resize', function() { // <-- Cambiar , por (
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768) {
        sidebar.classList.remove('mostrar');
        sidebar.classList.remove('ocultar');
    }
});