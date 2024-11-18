import requests
from bs4 import BeautifulSoup
import json
from datetime import datetime, timedelta
import pymysql

# Start and end dates
start_date = datetime(2023, 10, 1)
end_date = datetime(2024, 11, 18)

# Empty list to collect all data
all_gold_data = []

# Loop through each day in the date range
current_date = start_date
while current_date <= end_date:
    day = current_date.strftime("%d")
    month = current_date.strftime("%m")
    year = current_date.strftime("%Y")

    url = f"https://giavang.pnj.com.vn/history?gold_history_day={day}&gold_history_month={month}&gold_history_year={year}"
    response = requests.get(url)

    if response.status_code == 200:
        soup = BeautifulSoup(response.content, 'html.parser')
        target_div = soup.find('section',
                               id='portlet_com_pnj_gold_price_web_SearchGoldPriceResultPortlet_INSTANCE_3WGHuiSEaY89')
        daily_gold_data = {"date": current_date.strftime("%Y-%m-%d"), "regions": []}

        if target_div:
            for table in target_div.find_all('table'):
                region = table.find('th', class_='style1').text.strip().replace("Lịch sử giá vàng", "").strip()
                region_data = {
                    "Khu vực": region,
                    "Cac loai vang": []
                }
                current_type_title = None
                rows = table.find('tbody').find_all('tr')
                for row in rows[1:]:
                    columns = row.find_all('td')
                    if len(columns) == 4:
                        current_type_title = columns[0].text.strip()
                        gia_mua = columns[1].text.strip()
                        gia_ban = columns[2].text.strip()
                        thoi_gian_cap_nhat = columns[3].text.strip()
                    else:
                        gia_mua = columns[0].text.strip()
                        gia_ban = columns[1].text.strip()
                        thoi_gian_cap_nhat = columns[2].text.strip()

                    gold_type = next(
                        (item for item in region_data["Cac loai vang"] if item["Loại vàng"] == current_type_title),
                        None)
                    if not gold_type:
                        gold_type = {
                            "Loại vàng": current_type_title,
                            "Price": []
                        }
                        region_data["Cac loai vang"].append(gold_type)

                    gold_type["Price"].append({
                        "Giá mua": gia_mua,
                        "Giá bán": gia_ban,
                        "Thời gian cập nhật": thoi_gian_cap_nhat
                    })

                daily_gold_data["regions"].append(region_data)

            all_gold_data.append(daily_gold_data)
            print(f"Dữ liệu ngày {current_date.strftime('%Y-%m-%d')} đã được thu thập.")
        else:
            print(f"Không tìm thấy dữ liệu cho ngày {current_date.strftime('%Y-%m-%d')}.")
    else:
        print(
            f"Không thể truy cập trang web cho ngày {current_date.strftime('%Y-%m-%d')}. Mã trạng thái: {response.status_code}")

    # Move to the next day
    current_date += timedelta(days=1)

# Save all collected data to a JSON file
with open('gold_prices.json', 'w', encoding='utf-8') as json_file:
    json.dump(all_gold_data, json_file, ensure_ascii=False, indent=4)

print("Dữ liệu giá vàng đã được lưu vào 'gold_prices.json'")

# import json
#
# # Đường dẫn tới file gold_prices.json
# file_path = "gold_prices_jan_to_oct.json"
#
# # Mở và đọc file với mã hóa UTF-8
# with open(file_path, "r", encoding="utf-8") as file:
#     # Tải nội dung file vào biến data
#     data = json.load(file)
#
# # Xuất dữ liệu dưới dạng JSON với format dễ đọc
# pretty_json = json.dumps(data, indent=4, ensure_ascii=False)
# print(pretty_json)
#
# # Đường dẫn tới file JSON chứa dữ liệu đã cào
# file_path = "gold_prices_jan_to_oct.json"
#
# # Tải nội dung file JSON vào biến data
# with open(file_path, "r", encoding="utf-8") as file:
#     data = json.load(file)
#
# # Kết nối tới MySQL
# connection = pymysql.connect(
#     host="localhost",
#     user="root",
#     password="",  # Điền mật khẩu nếu có
#     database="goldprices"  # Thay bằng tên database của bạn
# )
#
# cursor = connection.cursor()
#
# cursor.execute('''
#     CREATE TABLE IF NOT EXISTS GoldPrices (
#         date DATE,
#         khu_vuc VARCHAR(255),
#         loai_vang VARCHAR(255),
#         gia_mua FLOAT,
#         gia_ban FLOAT,
#         thoi_gian_cap_nhat VARCHAR(255)
#     )
# ''')
#
# for daily_data in data:
#     date = daily_data["date"]
#     for region in daily_data["regions"]:
#         khu_vuc = region["Khu vực"]
#         for gold_type in region["Cac loai vang"]:
#             loai_vang = gold_type["Loại vàng"]
#             for price in gold_type["Price"]:
#                 gia_mua = float(price["Giá mua"].replace(",", ""))
#                 gia_ban = float(price["Giá bán"].replace(",", ""))
#                 thoi_gian_cap_nhat = price["Thời gian cập nhật"]
#
#                 cursor.execute('''
#                     INSERT INTO GoldPrices (date, khu_vuc, loai_vang, gia_mua, gia_ban, thoi_gian_cap_nhat)
#                     VALUES (%s, %s, %s, %s, %s, %s)
#                 ''', (date, khu_vuc, loai_vang, gia_mua, gia_ban, thoi_gian_cap_nhat))
#
# connection.commit()
# print("Dữ liệu đã được chèn thành công vào cơ sở dữ liệu MySQL!")
#
# cursor.close()
# connection.close()
# print("Đã đóng kết nối đến cơ sở dữ liệu.")
