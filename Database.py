import json
import pymysql
# Kết nối tới MySQL
connection = pymysql.connect(
    host="localhost",
    user="root",
    password="",  # Điền mật khẩu nếu có
    database="goldprices"  # Thay bằng tên database của bạn
)



cursor = connection.cursor()

# Tạo bảng trong MySQL
create_table = """
CREATE TABLE IF NOT EXISTS GoldPrices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date VARCHAR(10),
    khu_vuc VARCHAR(255),
    loai_vang VARCHAR(255),
    gia_mua VARCHAR(20),
    gia_ban VARCHAR(20),
    thoi_gian_cap_nhat VARCHAR(20)
);
"""
cursor.execute(create_table)

# Đọc dữ liệu từ file JSON
with open('gold_prices.json', encoding='utf-8') as file:
    data = json.load(file)

# Hàm để thêm dữ liệu vào bảng GoldPrices
def insert_data(date, khu_vuc, loai_vang, gia_mua, gia_ban, thoi_gian_cap_nhat):
    cursor.execute("""
        INSERT INTO GoldPrices (date, khu_vuc, loai_vang, gia_mua, gia_ban, thoi_gian_cap_nhat)
        VALUES (%s, %s, %s, %s, %s, %s)
    """, (date, khu_vuc, loai_vang, gia_mua, gia_ban, thoi_gian_cap_nhat))

# Xử lý dữ liệu từ file JSON và thêm vào bảng GoldPrices
for entry in data:
    date = entry["date"]
    for region in entry["regions"]:
        khu_vuc = region["Khu vực"]
        for gold in region["Cac loai vang"]:
            loai_vang = gold["Loại vàng"]
            for price in gold["Price"]:
                gia_mua = price["Giá mua"]
                gia_ban = price["Giá bán"]
                thoi_gian_cap_nhat = price["Thời gian cập nhật"]
                insert_data(date, khu_vuc, loai_vang, gia_mua, gia_ban, thoi_gian_cap_nhat)

# Lưu thay đổi và đóng kết nối
connection.commit()
cursor.close()
connection.close()
print("Dữ liệu đã được nhập vào bảng GoldPrices thành công.")