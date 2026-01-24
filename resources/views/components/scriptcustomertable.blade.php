<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput = document.querySelector('.search');
        const tableRows = document.querySelectorAll('.table tbody tr');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                tableRows.forEach(function(row) {
                    const filterCell = row.querySelector('td[data-label="Barang"]');
                    if (filterCell) {
                        const filterText = filterCell.textContent.toLowerCase().trim();
                        if (searchTerm === '' || filterText.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        }
    });
</script>