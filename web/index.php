<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "goldprices";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu từ form lọc
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$region = isset($_GET['region']) ? $_GET['region'] : '';
$gold_type = isset($_GET['gold_type']) ? $_GET['gold_type'] : '';

// Câu truy vấn với điều kiện lọc
$sql = "SELECT date, khu_vuc, loai_vang, gia_mua, gia_ban, thoi_gian_cap_nhat FROM goldprices WHERE 1=1";

if ($start_date && $end_date) {
    $sql .= " AND DATE(date) BETWEEN '$start_date' AND '$end_date'";
} elseif ($start_date) {
    $sql .= " AND DATE(date) >= '$start_date'";
} elseif ($end_date) {
    $sql .= " AND DATE(date) <= '$end_date'";
}
if ($region) {
    $sql .= " AND khu_vuc = '$region'";
}
if ($gold_type) {
    $sql .= " AND loai_vang = '$gold_type'";
}

$result = $conn->query($sql);

// Xuất dữ liệu ra file JSON
if (isset($_GET['export']) && $_GET['export'] == 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment;filename="goldprices.json"');
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit();
}




// Xuất file CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="goldprices.csv"');
    $output = fopen("php://output", "w");
    fputcsv($output, ['Ngày', 'Khu vực', 'Loại vàng', 'Giá mua', 'Giá bán', 'Thời gian cập nhật']);
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Xuất file Excel
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Ghi dòng tiêu đề
    $sheet->setCellValue('A1', 'Ngày')
          ->setCellValue('B1', 'Khu vực')
          ->setCellValue('C1', 'Loại vàng')
          ->setCellValue('D1', 'Giá mua')
          ->setCellValue('E1', 'Giá bán')
          ->setCellValue('F1', 'Thời gian cập nhật');
    
    // Ghi dữ liệu vào các hàng tiếp theo
    $rowIndex = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue("A{$rowIndex}", $row['date'])
              ->setCellValue("B{$rowIndex}", $row['khu_vuc'])
              ->setCellValue("C{$rowIndex}", $row['loai_vang'])
              ->setCellValue("D{$rowIndex}", $row['gia_mua'])
              ->setCellValue("E{$rowIndex}", $row['gia_ban'])
              ->setCellValue("F{$rowIndex}", $row['thoi_gian_cap_nhat']);
        $rowIndex++;
    }

    // Xuất file Excel
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="goldprices.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dữ Liệu Giá Vàng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Dữ Liệu Giá Vàng</h1>

    <!-- Form lọc dữ liệu -->
    <form method="GET" action="index.php">
    <label for="start_date">Ngày bắt đầu:</label>
    <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">

    <label for="end_date">Ngày kết thúc:</label>
    <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">

    <label for="region">Khu vực:</label>
    <select id="region" name="region">
        <option value="">Tất cả</option>
        <option value="TPHCM" <?= $region == "TPHCM" ? 'selected' : '' ?>>TPHCM</option>
        <option value="Hà Nội" <?= $region == "Hà Nội" ? 'selected' : '' ?>>Hà Nội</option>
        <option value="Đà Nẵng" <?= $region == "Đà Nẵng" ? 'selected' : '' ?>>Đà Nẵng</option>
        <option value="Miền Tây" <?= $region == "Miền Tây" ? 'selected' : '' ?>>Miền Tây</option>
        <option value="Tây Nguyên" <?= $region == "Tây Nguyên" ? 'selected' : '' ?>>Tây Nguyên</option>
        <option value="Đông Nam Bộ" <?= $region == "Đông Nam Bộ" ? 'selected' : '' ?>>Đông Nam Bộ</option>
        <option value="Giá vàng nữ trang" <?= $region == "Giá vàng nữ trang" ? 'selected' : '' ?>>Giá vàng nữ trang</option>
    </select>

    <label for="gold_type">Loại vàng:</label>
    <select id="gold_type" name="gold_type">
        <option value="">Tất cả</option>
        <option value="PNJ" <?= $gold_type == "PNJ" ? 'selected' : '' ?>>PNJ</option>
        <option value="SJC" <?= $gold_type == "SJC" ? 'selected' : '' ?>>SJC</option>
        <option value="Nhẫn Trơn PNJ 999.9" <?= $gold_type == "Nhẫn Trơn PNJ 999.9" ? 'selected' : '' ?>>Nhẫn Trơn PNJ 999.9</option>
        <option value="Vàng nữ trang 999.9" <?= $gold_type == "Vàng nữ trang 999.9" ? 'selected' : '' ?>>Vàng nữ trang 999.9</option>
        <option value="Vàng nữ trang 999" <?= $gold_type == "Vàng nữ trang 999" ? 'selected' : '' ?>>Vàng nữ trang 999</option>
        <option value="Vàng nữ trang 99" <?= $gold_type == "Vàng nữ trang 99" ? 'selected' : '' ?>>Vàng nữ trang 99</option>
        <option value="Vàng 916 (22K)" <?= $gold_type == "Vàng 916 (22K)" ? 'selected' : '' ?>>Vàng 916 (22K)</option>
        <option value="Vàng 750 (18K)" <?= $gold_type == "Vàng 750 (18K)" ? 'selected' : '' ?>>Vàng 750 (18K)</option>
        <option value="Vàng 680 (16.3K)" <?= $gold_type == "Vàng 680 (16.3K)" ? 'selected' : '' ?>>Vàng 680 (16.3K)</option>
        <option value="Vàng 650 (15.6K)" <?= $gold_type == "Vàng 650 (15.6K)" ? 'selected' : '' ?>>Vàng 650 (15.6K)</option>
        <option value="Vàng 610 (14.6K)" <?= $gold_type == "Vàng 610 (14.6K)" ? 'selected' : '' ?>>Vàng 610 (14.6K)</option>
        <option value="Vàng 585 (14K)" <?= $gold_type == "Vàng 585 (14K)" ? 'selected' : '' ?>>Vàng 585 (14K)</option>
        <option value="Vàng 416 (10K)" <?= $gold_type == "Vàng 416 (10K)" ? 'selected' : '' ?>>Vàng 416 (10K)</option>
        <option value="Vàng 375 (9K)" <?= $gold_type == "Vàng 375 (9K)" ? 'selected' : '' ?>>Vàng 375 (9K)</option>
        <option value="Vàng 333 (8K)" <?= $gold_type == "Vàng 333 (8K)" ? 'selected' : '' ?>>Vàng 333 (8K)</option>
    </select>

    <button type="submit">Lọc</button>
    <a href="charts.php" class="chart-link"><button type="button">Xem Biểu Đồ</button></a>
</form>


    <!-- Nút xuất dữ liệu -->
    <div class="export-buttons">
        <a href="index.php?export=csv&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&region=<?= $region ?>&gold_type=<?= $gold_type ?>"><button>Xuất CSV</button></a>
        <a href="index.php?export=excel&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&region=<?= $region ?>&gold_type=<?= $gold_type ?>"><button>Xuất Excel</button></a>
        <a href="index.php?export=json&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&region=<?= $region ?>&gold_type=<?= $gold_type ?>"><button>Xuất JSON</button></a>
    </div>

    <!-- Bảng hiển thị dữ liệu -->
    <table>
        <tr>
            <th>Ngày</th>
            <th>Khu vực</th>
            <th>Loại vàng</th>
            <th>Giá mua</th>
            <th>Giá bán</th>
            <th>Thời gian cập nhật</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['khu_vuc'] ?></td>
                    <td><?= $row['loai_vang'] ?></td>
                    <td><?= $row['gia_mua'] ?></td>
                    <td><?= $row['gia_ban'] ?></td>
                    <td><?= $row['thoi_gian_cap_nhat'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Không có dữ liệu nào</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
