<?php
$price_type = isset($_GET['price_type']) ? htmlspecialchars($_GET['price_type']) : '';
if ($price_type !== 'mua' && $price_type !== 'ban') {
    die('Loại giá không hợp lệ.');
}
?>
<!DOCTYPE html>
<html lang="vi">
<link rel="stylesheet" href="style.css?v=<?= time(); ?>">

<head>
    <meta charset="UTF-8">
    <title>Chọn Biểu Đồ Giá <?= $price_type === 'mua' ? 'Mua' : 'Bán' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Chọn Biểu Đồ Giá <?= $price_type === 'mua' ? 'Mua' : 'Bán' ?></h1>
    <div class="chart-options">
        <ul>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=PNJ">PNJ</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=SJC">SJC</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Nhẫn Trơn PNJ 999.9">Nhẫn Trơn PNJ 999.9</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng nữ trang 999.9">Vàng nữ trang 999.9</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng nữ trang 999">Vàng nữ trang 999</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng nữ trang 99">Vàng nữ trang 99</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 916 (22K)">Vàng 916 (22K)</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 750 (18K)">Vàng 750 (18K)</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 680 (16.3K)">Vàng 680 (16.3K)</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 650 (15.6K)">Vàng 650 (15.6K)</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 610 (14.6K)">Vàng 610 (14.6K)</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 585 (14K)">Vàng 585 (14K)</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 416 (10K)">Vàng 416 (10K)</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 375 (9K)">Vàng 375 (9K)</a></li>
            <li><a href="chart.php?price_type=<?= $price_type ?>&type=Vàng 333 (8K)">Vàng 333 (8K)</a></li>
        </ul>
    </div>
    <div class="back-button">
        <a href="charts.php"><button>Quay Lại</button></a>
    </div>
</body>
</html>
