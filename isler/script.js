document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const tableBody = document.querySelector('#data-table tbody');

    function fetchData(search = '') {
        fetch(`fetch.php?search=${search}`)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                data.forEach(row => {
                    const durumClass = row.durum.toLowerCase();
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.tarih}</td>
                        <td>${row.sube}</td>
                        <td>${row.kisi}</td>
                        <td><input type="text" value="${row.iletisim}" maxlength="11" class="editable iletisim" data-id="${row.id}" /></td>
                        <td>${row.konu}</td>
                        <td>
                            <select class="editable durum ${durumClass}" data-id="${row.id}">
                                <option value="Tamamlandı" ${row.durum === 'Tamamlandı' ? 'selected' : ''}>Tamamlandı</option>
                                <option value="Tamamlanmadı" ${row.durum === 'Tamamlanmadı' ? 'selected' : ''}>Tamamlanmadı</option>
                                <option value="Bilgi" ${row.durum === 'Bilgi' ? 'selected' : ''}>Bilgi</option>
                            </select>
                        </td>
                    `;
                    tableBody.appendChild(tr);
                });

                // Add event listeners to editable elements
                document.querySelectorAll('.editable').forEach(element => {
                    element.addEventListener('change', function() {
                        const id = this.dataset.id;
                        const column = this.classList.contains('iletisim') ? 'iletisim' : 'durum';
                        const value = this.value;
                        
                        updateData(id, column, value);
                    });
                });
            });
    }

    function updateData(id, column, value) {
        fetch(`update.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id, column, value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchData();
            } else {
                console.error('Update failed');
            }
        });
    }

    fetchData();

    searchInput.addEventListener('input', function() {
        fetchData(this.value);
    });
});