// public/assets/js/mesas.js

document.addEventListener('DOMContentLoaded', () => {
    // ==========================================
    // FILTROS DE MESAS
    // ==========================================
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.mesa-card-wrapper');

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;

            // Actualizar botón activo
            filterButtons.forEach((btn) => btn.classList.remove('active'));
            button.classList.add('active');

            // Filtrar tarjetas
            cards.forEach((card) => {
                const status = card.dataset.status;
                const shouldShow = filter === 'todas' || status === filter;
                card.style.display = shouldShow ? '' : 'none';
            });
        });
    });

    // ==========================================
    // MODAL DE OCUPAR MESA
    // ==========================================
    const modal = new bootstrap.Modal(document.getElementById('mesaModal'));
    const modalTitle = document.getElementById('mesaModalTitle');
    const modalBody = document.getElementById('mesaModalBody');
    const modalFooter = document.querySelector('#mesaModal .modal-footer');

    document.querySelectorAll('.btn-ocupacion').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const numero = button.dataset.numero;

            modalTitle.textContent = 'Abrir Mesa ' + numero;

            modalBody.innerHTML = `
                <p class="mb-3">¿Deseas asignar esta mesa a un grupo de clientes?</p>
                <form method="POST" action="index.php?action=mesas_ocupar" id="formOcuparMesa">
                    <input type="hidden" name="id_mesa" value="${id}">
                    <div class="mb-3">
                        <label for="comensales" class="form-label fw-bold">Número de comensales:</label>
                        <input type="number" id="comensales" name="comensales" class="form-control" min="1" max="20" value="2" required>
                    </div>
                </form>
            `;

            modalFooter.innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formOcuparMesa" class="btn btn-success">Ocupar Mesa</button>
            `;

            modal.show();
        });
    });

    // ==========================================
    // MODAL DE COBRAR TICKET
    // ==========================================
    document.querySelectorAll('.btn-cobrar').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const numero = button.dataset.numero;

            modalTitle.textContent = 'Cobrar Ticket - ' + numero;

            modalBody.innerHTML = `
                <p class="mb-3">¿Confirmas el cobro de la mesa <strong>${numero}</strong>?</p>
                <p class="text-muted small">Al confirmar, la mesa se liberará y quedará disponible para nuevos clientes.</p>
            `;

            modalFooter.innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="index.php?action=mesas_liberar&id=${id}" class="btn btn-success">Cobrar y Liberar</a>
            `;

            modal.show();
        });
    });
});