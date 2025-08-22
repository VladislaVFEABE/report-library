<?php
session_start();
if (!isset($_POST['download'])) {
    header('Location: index.php');
    exit;
}

$col_names = $_POST['col_name'] ?? [];
$col_colors = $_POST['col_color'] ?? [];
$data = $_POST['data'] ?? [];

$format = $_POST['download'];

// Сохраняем данные в сессии
$_SESSION['col_names'] = $col_names;
$_SESSION['col_colors'] = $col_colors;
$_SESSION['data'] = $data;

// CSV экспорт
if ($format === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="document.csv"');
    echo "\xEF\xBB\xBF";
    $out = fopen('php://output', 'w');
    fputcsv($out, $col_names);
    foreach ($data as $row) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}

// HTML экспорт с цветами
if ($format === 'html') {
    header('Content-Type: text/html; charset=UTF-8');
    header('Content-Disposition: attachment; filename="document.html"');
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>";
    foreach ($col_names as $i=>$name) {
        $color = $col_colors[$i] ?? '#ffffff';
        echo "<th style='background-color:$color'>$name</th>";
    }
    echo "</tr>";
    foreach ($data as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td style='background-color:#fff'>$cell</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    exit;
}
