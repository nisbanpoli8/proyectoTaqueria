document.addEventListener('DOMContentLoaded', () => {
    // 1. FILTROS
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.mesa-card-wrapper');

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;
            filterButtons.forEach((btn) => btn.classList.remove('active'));
            button.classList.add('active');

            cards.forEach((card) => {
                const status = card.dataset.status;
                const shouldShow = (filter === 'todas' || status === filter);
                card.style.display = shouldShow ? '' : 'none';
            });
        });
    });

    // 2. MODAL OCUPAR
    const modalElement = document.getElementById('mesaModal');
    const modal = new bootstrap.Modal(modalElement);
    const modalTitle = document.getElementById('mesaModalTitle');
    const modalBody = document.getElementById('mesaModalBody');
    const modalFooter = document.getElementById('modalFooter');

    document.querySelectorAll('.btn-ocupacion').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const numero = button.dataset.numero;
            const capacidad = button.dataset.capacidad;

            modalTitle.textContent = 'Abrir ' + numero;

            modalBody.innerHTML = `
                <p class="mb-3">¿Deseas asignar esta mesa a un grupo de clientes?</p>
                <form method="POST" action="index.php?action=mesas_ocupar" id="formOcuparMesa">
                    <input type="hidden" name="id_mesa" value="${id}">
                    <div class="mb-3">
                        <label for="comensales" class="form-label fw-bold">Número de personas:</label>
                        <input type="number" id="comensales" name="comensales" class="form-control" 
                               min="1" max="${capacidad}" value="1" required>
                        <div class="form-text text-danger fw-bold mt-1" id="alerta-capacidad" style="display: none;">
                            ¡La capacidad máxima de esta mesa es de ${capacidad} personas!
                        </div>
                    </div>
                </form>
            `;

            modalFooter.innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formOcuparMesa" class="btn btn-success" id="btnGuardarMesa">Ocupar Mesa</button>
            `;

            // Validación de capacidad
            const inputComensales = document.getElementById('comensales');
            const alerta = document.getElementById('alerta-capacidad');
            const btnGuardar = document.getElementById('btnGuardarMesa');

            inputComensales.addEventListener('input', function () {
                if (parseInt(this.value) > parseInt(this.max)) {
                    alerta.style.display = 'block';
                    this.classList.add('is-invalid');
                    btnGuardar.disabled = true;
                } else {
                    alerta.style.display = 'none';
                    this.classList.remove('is-invalid');
                    btnGuardar.disabled = false;
                }
            });

            modal.show();
        });
    });

    // 3. MODAL COBRAR
    document.querySelectorAll('.btn-cobrar').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const numero = button.dataset.numero;

            modalTitle.textContent = 'Cobrar Ticket - ' + numero;
            modalBody.innerHTML = `
                <p class="mb-3">¿Confirmas el cobro de la mesa <strong>${numero}</strong>?</p>
                <p class="text-muted small">Al confirmar, la mesa se liberará.</p>
            `;

            modalFooter.innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="index.php?action=mesas_liberar&id=${id}" class="btn btn-success">Cobrar y Liberar</a>
            `;

            modal.show();
        });
    });
});