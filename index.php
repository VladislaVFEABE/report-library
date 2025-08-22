<?php
session_start();

// Если есть сохранённые данные, используем их
$cols = $_SESSION['cols'] ?? 3;
$rows = $_SESSION['rows'] ?? 3;
$col_names = $_SESSION['col_names'] ?? array_fill(0, $cols, '');
$col_colors = $_SESSION['col_colors'] ?? array_fill(0, $cols, '#ffffff');
$data = $_SESSION['data'] ?? array_fill(0, $rows, array_fill(0, $cols, ''));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Мини Excel на PHP</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Мини Excel</h1>

<form method="post">
    <label>Количество колонок: <input type="number" name="cols" value="<?= $cols ?>" min="1"></label>
    <label>Количество строк: <input type="number" name="rows" value="<?= $rows ?>" min="1"></label>
    <button type="submit" name="create">Создать/Обновить таблицу</button>
</form>

<?php
if (isset($_POST['create'])) {
    $cols = (int)$_POST['cols'];
    $rows = (int)$_POST['rows'];

    // Обновляем массивы для новой таблицы
    $col_names = array_fill(0, $cols, '');
    $col_colors = array_fill(0, $cols, '#ffffff');
    $data = array_fill(0, $rows, array_fill(0, $cols, ''));

    // Сохраняем в сессию
    $_SESSION['cols'] = $cols;
    $_SESSION['rows'] = $rows;
    $_SESSION['col_names'] = $col_names;
    $_SESSION['col_colors'] = $col_colors;
    $_SESSION['data'] = $data;
}
?>

<form method="post" action="generate.php">
    <table id="excelTable" border="1" cellpadding="5">
        <tr>
            <?php for ($c=0; $c<$cols; $c++): ?>
            <th>
                Название:<br>
                <input type="text" name="col_name[]" value="<?= $col_names[$c] ?>">
                <br>Цвет:<br>
                <input type="color" name="col_color[]" value="<?= $col_colors[$c] ?>">
                <br><button type="button" onclick="removeColumn(<?= $c ?>)">X</button>
            </th>
            <?php endfor; ?>
            <th><button type="button" onclick="addColumn()">+</button></th>
        </tr>
        <?php for ($r=0; $r<$rows; $r++): ?>
        <tr>
            <?php for ($c=0; $c<$cols; $c++): ?>
            <td style="background-color: <?= $data[$r][$c] ?? '#fff' ?>;">
                <input type="text" name="data[<?= $r ?>][<?= $c ?>]" value="<?= $data[$r][$c] ?? '' ?>">
            </td>
            <?php endfor; ?>
            <td><button type="button" onclick="removeRow(<?= $r ?>)">X</button></td>
        </tr>
        <?php endfor; ?>
        <tr><td colspan="<?= $cols+1 ?>"><button type="button" onclick="addRow()">Добавить строку</button></td></tr>
    </table>
    <br>
    <button type="submit" name="download" value="csv">Скачать CSV</button>
    <button type="submit" name="download" value="html">Скачать HTML</button>
</form>

<script src="js/table.js"></script>
<script>
    makeColumnsDraggable();
    makeRowsDraggable();
</script>
</body>
</html>
