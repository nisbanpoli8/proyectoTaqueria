document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('comandaTableBody');

    if (!table) {
        return;
    }

    const updateTotals = () => {
        let subtotal = 0;

        table.querySelectorAll('tr[data-price]').forEach((row) => {
            const price = Number.parseFloat(row.dataset.price || '0');
            const qtyInput = row.querySelector('.cantidad-input');
            const qty = Number.parseInt(qtyInput?.value || '0', 10);
            const rowSubtotal = price * qty;
            const subtotalCell = row.querySelector('.subtotal-item');

            if (subtotalCell) {
                subtotalCell.textContent = '$' + rowSubtotal.toFixed(2);
            }

            subtotal += rowSubtotal;
        });

        const subtotalValue = document.getElementById('subtotal-valor');
        if (subtotalValue) {
            subtotalValue.textContent = '$' + subtotal.toFixed(2);
        }
    };

    table.querySelectorAll('.cantidad-input').forEach((input) => {
        input.addEventListener('change', updateTotals);
    });

    updateTotals();
});
