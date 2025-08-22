function addRow() {
    let table = document.getElementById('excelTable');
    let row = table.insertRow(table.rows.length - 1);
    let cols = table.rows[0].cells.length - 1; // последний + для кнопки
    for (let c = 0; c < cols; c++) {
        let cell = row.insertCell(c);
        cell.innerHTML = '<input type="text" name="data[new]['+c+']">';
    }
    let lastCell = row.insertCell(cols);
    lastCell.innerHTML = '<button type="button" onclick="removeRow(this)">X</button>';
}

function removeRow(r) {
    let row;
    if (typeof r === 'number') {
        row = document.getElementById('excelTable').rows[r+1];
    } else {
        row = r.closest('tr');
    }
    row.parentNode.removeChild(row);
}

function addColumn() {
    let table = document.getElementById('excelTable');
    let rows = table.rows;
    for (let r=0; r<rows.length-1; r++) {
        let cell = rows[r].insertCell(rows[r].cells.length-1);
        if (r==0) {
            cell.innerHTML = 'Название:<br><input type="text" name="col_name[]"><br>Цвет:<br><input type="color" name="col_color[]"><br><button type="button" onclick="removeColumn('+ (rows[r].cells.length-2) +')">X</button>';
        } else {
            cell.innerHTML = '<input type="text" name="data[new]['+(rows[r].cells.length-2)+']">';
        }
    }
}

function removeColumn(c) {
    let table = document.getElementById('excelTable');
    for (let r=0; r<table.rows.length-1; r++) {
        table.rows[r].deleteCell(c);
    }
}

// table.js
let draggedColIndex = null;

function makeColumnsDraggable() {
    const headers = document.querySelectorAll('#excelTable th');
    headers.forEach((th, index) => {
        th.setAttribute('draggable', true);

        th.addEventListener('dragstart', (e) => {
            draggedColIndex = index;
            e.dataTransfer.effectAllowed = "move";
        });

        th.addEventListener('dragover', (e) => e.preventDefault());

        th.addEventListener('drop', (e) => {
            e.preventDefault();
            if (draggedColIndex === null || draggedColIndex === index) return;

            const table = document.getElementById('excelTable');
            const rows = table.rows;

            for (let r = 0; r < rows.length; r++) {
                let cells = Array.from(rows[r].cells);
                const draggedCell = cells[draggedColIndex];
                if (draggedColIndex < index) {
                    rows[r].insertBefore(draggedCell, cells[index].nextSibling);
                } else {
                    rows[r].insertBefore(draggedCell, cells[index]);
                }
            }
            draggedColIndex = null;
        });
    });
}

function moveColumn(from, to) {
    const table = document.getElementById('excelTable');
    for (let r=0; r<table.rows.length; r++) {
        let cells = table.rows[r].cells;
        let cell = cells[from];
        table.rows[r].insertBefore(cell, cells[to]);
    }
}

// Вызовем после создания таблицы
makeColumnsDraggable();


let draggedRow = null;

function makeRowsDraggable() {
    const table = document.getElementById('excelTable');
    Array.from(table.rows).forEach(row => {
        row.setAttribute('draggable', true);

        row.addEventListener('dragstart', (e) => {
            draggedRow = row;
            e.dataTransfer.effectAllowed = "move";
        });

        row.addEventListener('dragover', (e) => e.preventDefault());

        row.addEventListener('drop', (e) => {
            e.preventDefault();
            if (!draggedRow || draggedRow === row) return;

            table.insertBefore(draggedRow, row.nextSibling);
            draggedRow = null;
        });
    });
}

// Вызов после создания таблицы
makeRowsDraggable();
