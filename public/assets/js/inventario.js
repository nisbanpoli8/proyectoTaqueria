const buscadorInventario = document.getElementById('buscador');

if (buscadorInventario) {
    buscadorInventario.addEventListener('input', () => {
        const filtro = buscadorInventario.value.toLowerCase();

        document.querySelectorAll('.fila-insumo').forEach((fila) => {
            const nombre = fila.querySelector('.nombre-insumo').textContent.toLowerCase();
            fila.style.display = nombre.includes(filtro) ? '' : 'none';
        });
    });
}
