<?php
$price_type = isset($_GET['price_type']) ? htmlspecialchars($_GET['price_type']) : '';
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';

if ($price_type !== 'mua' && $price_type !== 'ban') {
    die('Loại giá không hợp lệ.');
}

$image_map = [
    'PNJ' => "VangPNJ_{$price_type}.png",
    'SJC' => "VangSJC_{$price_type}.png",
    'Nhẫn Trơn PNJ 999.9' => "PNJ999.9_{$price_type}.png",
    'Vàng nữ trang 999.9' => "VangNuTrang999.9_{$price_type}.png",
    'Vàng nữ trang 999' => "VangNuTrang999_{$price_type}.png",
    'Vàng nữ trang 99' => "VangNuTrang99_{$price_type}.png",
    'Vàng 916 (22K)' => "Vang916_{$price_type}.png",
    'Vàng 750 (18K)' => "Vang750_{$price_type}.png",
    'Vàng 680 (16.3K)' => "Vang680_{$price_type}.png",
    'Vàng 650 (15.6K)' => "Vang650_{$price_type}.png",
    'Vàng 610 (14.6K)' => "Vang610_{$price_type}.png",
    'Vàng 585 (14K)' => "Vang585_{$price_type}.png",
    'Vàng 416 (10K)' => "Vang416_{$price_type}.png",
    'Vàng 375 (9K)' => "Vang375_{$price_type}.png",
    'Vàng 333 (8K)' => "Vang333_{$price_type}.png",
];

$image_file = isset($image_map[$type]) ? $image_map[$type] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Biểu Đồ Giá <?= $price_type === 'mua' ? 'Mua' : 'Bán' ?> - <?= $type ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Biểu Đồ Giá <?= $price_type === 'mua' ? 'Mua' : 'Bán' ?> - <?= $type ?></h1>
    <div class="chart-images">
        <?php if ($image_file && file_exists("images/{$image_file}")): ?>
            <img src="images/<?= $image_file ?>" alt="Biểu đồ <?= $type ?>" style="max-width: 60%; height: auto; margin: 20px auto; display: block;">
        <?php else: ?>
            <p>Không tìm thấy biểu đồ cho loại vàng này.</p>
        <?php endif; ?>
    </div>
    <div class="back-button">
        <a href="charts_by_type.php?price_type=<?= $price_type ?>"><button>Quay Lại</button></a>
    </div>
</body>
</html>
