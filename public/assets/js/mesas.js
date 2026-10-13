document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.mesa-card-wrapper');

    const statusMap = {
        libres: 'libre',
        ocupadas: 'ocupada',
        cuenta: 'cuenta'
    };

    const applyFilter = (filter) => {
        const mappedStatus = statusMap[filter] || 'all';

        cards.forEach((card) => {
            const status = card.dataset.status;
            const shouldShow = mappedStatus === 'all' || status === mappedStatus;
            card.style.display = shouldShow ? '' : 'none';
        });

        filterButtons.forEach((button) => {
            const active = button.dataset.filter === filter;
            button.classList.toggle('active', active);
        });
    };

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => applyFilter(button.dataset.filter));
    });

    const modal = new bootstrap.Modal(document.getElementById('mesaModal'));
    const modalTitle = document.getElementById('mesaModalTitle');
    const modalBody = document.getElementById('mesaModalBody');

    document.querySelectorAll('.btn-ocupacion').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const numero = button.dataset.numero;
            modalTitle.textContent = 'Abrir Mesa';
            modalBody.innerHTML = `
                <p class="mb-3">Mesa <strong>${numero}</strong> lista para abrir.</p>
                <form method="POST" action="index.php?action=mesas_ocupar">
                    <input type="hidden" name="id_mesa" value="${id}">
                    <div class="mb-3">
                        <label for="comensales" class="form-label">Número de comensales</label>
                        <input type="number" id="comensales" name="comensales" class="form-control" min="1" max="20" value="2" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Confirmar</button>
                </form>
            `;
            modal.show();
        });
    });

    document.querySelectorAll('.btn-ver-comanda').forEach((button) => {
        button.addEventListener('click', () => {
            const numero = button.dataset.numero;
            modalTitle.textContent = 'Ver Comanda';
            modalBody.innerHTML = `
                <p class="mb-2">Mesa <strong>${numero}</strong> está ocupada.</p>
                <p class="text-muted mb-0">Comanda actual: tacos, refrescos y guarniciones pendientes.</p>
            `;
            modal.show();
        });
    });

    document.querySelectorAll('.btn-cobrar').forEach((button) => {
        button.addEventListener('click', () => {
            const numero = button.dataset.numero;
            modalTitle.textContent = 'Cobrar Ticket';
            modalBody.innerHTML = `
                <p class="mb-2">¿Deseas cobrar la cuenta de la mesa <strong>${numero}</strong>?</p>
                <p class="text-muted mb-0">El cobro del ticket liberará la mesa al confirmar.</p>
            `;
            const footer = document.querySelector('#mesaModal .modal-footer');
            footer.innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="index.php?action=mesas_liberar&id=${button.dataset.id}" class="btn btn-success">Cobrar</a>
            `;
            modal.show();
        });
    });
});
