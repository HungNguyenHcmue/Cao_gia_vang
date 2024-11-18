<!DOCTYPE html>
<html lang="vi">
<link rel="stylesheet" href="style.css?v=<?= time(); ?>">

<head>
    <meta charset="UTF-8">
    <title>Chọn Loại Biểu Đồ Giá Vàng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Chọn Loại Biểu Đồ Giá Vàng</h1>
    <div class="chart-type-options">
        <ul>
            <li><a href="charts_by_type.php?price_type=mua">Biểu đồ giá mua</a></li>
            <li><a href="charts_by_type.php?price_type=ban">Biểu đồ giá bán</a></li>
        </ul>
    </div>
    <div class="back-button">
        <a href="index.php"><button>Quay Lại</button></a>
    </div>
</body>
</html>
