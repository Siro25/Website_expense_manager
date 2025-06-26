# 💰 Ứng dụng Quản lý Chi tiêu (Expense Manager)

---

## 👨‍🎓 Thông tin sinh viên
- **Họ và tên:** Đỗ Tiến Sĩ  
- **MSSV:** 23010577  
- **Lớp:** K17-CNTT6  
- **Môn học:** Thiết kế Web nâng cao (TH3)

---

## 📌 Giới thiệu dự án

**Expense Manager** là hệ thống web giúp người dùng theo dõi, quản lý và phân tích các khoản thu chi cá nhân hiệu quả.  

### 🎯 Các tính năng chính:
- 🧾 Quản lý chi tiêu: thêm, sửa, xóa các khoản chi
- 💸 Quản lý thu nhập
- 🗂️ Phân loại danh mục
- 📊 Báo cáo thống kê trực quan
- 📋 Bảng điều khiển tổng quan

---

## 🧪 Công nghệ sử dụng

### 🖥️ Backend
- **PHP 8.2**
- **Laravel 12**
- **MySQL**
- **Eloquent ORM**

### 🎨 Frontend
- **Blade Template Engine**
- **TailwindCSS**
- **JavaScript / Alpine.js**
- **Chart.js**

### 🛠️ Công cụ phát triển
- **Composer**
- **Git**
- **XAMPP**
- **Railway**

---

## 🧭 Sơ đồ hệ thống

### 📦 Sơ đồ khối


```
+-------------------+      +-------------------+      +-------------------+
|                   |      |                   |      |                   |
|  Người dùng       |----->|  Ứng dụng Web     |----->|  Cơ sở dữ liệu    |
|  (User Interface) |      |  (Laravel)        |      |  (MySQL)          |
|                   |      |                   |      |                   |
+-------------------+      +-------------------+      +-------------------+
         ^                          |                          |
         |                          v                          |
         |                  +-------------------+              |
         |                  |                   |              |
         +------------------|  Xử lý nghiệp vụ  |<-------------+
                            |  (Controllers)    |
                            |                   |
                            +-------------------+
```

### 🔐 Sơ đồ đăng nhập

```
+-------------------+     +-------------------+     +-------------------+
|                   |     |                   |     |                   |
|  Trang đăng nhập  |---->|  Xác thực thông   |---->|  Chuyển hướng đến |
|  (Login Form)     |     |  tin đăng nhập    |     |  trang chủ        |
|                   |     |  (Authentication) |     |  (Dashboard)      |
+-------------------+     +-------------------+     +-------------------+
| email: string     |     | +login()          |   
| password: string  |     | +authenticate()   |     
| +submit()         |     | +validate()       |     
+-------------------+     +-------------------+    
          |                         |
          |                         | Thất bại
          v                         v
+-------------------+     +-------------------+
|                   |     |                   |
|  Trang đăng ký    |     |  Hiển thị thông   |
|  (Register Form)  |     |  báo lỗi          |
|                   |     |  (Error Message)  |
+-------------------+     +-------------------+
| name: string      |     | message: string   |
| email: string     |     | type: string      |
| password: string  |     | +display()        |
| +submit()         |     +-------------------+
+-------------------+
```


### 🔄 Sơ đồ CRUD



```
+------------------------+       +------------------------+       +------------------------+
|      Controller        |       |         Model          |       |         View           |
+------------------------+       +------------------------+       +------------------------+
| +index()               |------>| +all()                 |------>| list.blade.php         |
| +create()              |       | +find(id)              |       | create.blade.php       |
| +store(request)        |       | +create(data)          |       | edit.blade.php         |
| +show(id)              |       | +update(id, data)      |       | show.blade.php         |
| +edit(id)              |       | +delete(id)            |       |                        |
| +update(request, id)   |       |                        |       |                        |
| +destroy(id)           |       |                        |       |                        |
+------------------------+       +------------------------+       +------------------------+
         |                                  |                               |
         |                                  |                               |
         v                                  v                               v
+------------------------+       +------------------------+       +------------------------+
|      Database          |       |      Validation        |       |      Response          |
+------------------------+       +------------------------+       +------------------------+
| +query()               |       | +rules()               |       | +view()                |
| +insert()              |       | +messages()            |       | +redirect()            |
| +update()              |       | +validate()            |       | +json()                |
| +delete()              |       |                        |       | +download()            |
+------------------------+       +------------------------+       +------------------------+
```

---

## 📱 Các đối tượng chính trong ứng dụng

### 👤 User (Người dùng)
- **Thuộc tính**: id, name, email, password, created_at, updated_at
- **Chức năng**: Đăng ký, đăng nhập, quản lý thông tin cá nhân
- **Quan hệ**: Một User có nhiều Expense, Income, Category, IncomeCategory

### 💰 Expense (Chi tiêu)
- **Thuộc tính**: id, amount, description, date, user_id, category_id, created_at, updated_at
- **Chức năng**: Ghi lại các khoản chi tiêu
- **Quan hệ**: Thuộc về một User và một Category

### 💵 Income (Thu nhập)
- **Thuộc tính**: id, amount, description, date, user_id, income_category_id, created_at, updated_at
- **Chức năng**: Ghi lại các khoản thu nhập
- **Quan hệ**: Thuộc về một User và một IncomeCategory

### 📂 Category (Danh mục chi tiêu)
- **Thuộc tính**: id, name, description, user_id, created_at, updated_at
- **Chức năng**: Phân loại các khoản chi tiêu
- **Quan hệ**: Thuộc về một User và có nhiều Expense

### 📂 IncomeCategory (Danh mục thu nhập)
- **Thuộc tính**: id, name, user_id, created_at, updated_at
- **Chức năng**: Phân loại các khoản thu nhập
- **Quan hệ**: Thuộc về một User và có nhiều Income

---

## 🔐 Bảo mật

- ✅ Xác thực người dùng (Authentication)
- ✅ Phân quyền theo tài khoản (Authorization)
- ✅ Chống tấn công (CSRF) 
- ✅ Mã hóa mật khẩu (bcrypt)
- ✅ Kiểm tra đầu vào (Data Validation)
- ✅ Quản lý phiên làm việc an toàn (Session Management)
- ✅ Cookies Security**: Cookie được bảo mật
- ✅ SQL Injection Prevention (Eloquent ORM)

---

## 🗃️ Cấu trúc Database

### 📊 Bảng users
- id (PK)
- name
- email
- email_verified_at
- password
- remember_token
- created_at
- updated_at

### 📊 Bảng categories
- id (PK)
- name
- description
- user_id (FK)
- created_at
- updated_at

### 📊 Bảng expenses
- id (PK)
- amount
- description
- date
- user_id (FK)
- category_id (FK)
- created_at
- updated_at

### 📊 Bảng income_categories
- id (PK)
- name
- user_id (FK)
- created_at
- updated_at

### 📊 Bảng incomes
- id (PK)
- amount
- description
- date
- user_id (FK)
- income_category_id (FK)
- created_at
- updated_at

---

## 📖 Hướng dẫn sử dụng chi tiết

### 🔧 Cài đặt

1. **Yêu cầu hệ thống**
   - PHP >= 8.2
   - Composer
   - MySQL hoặc MariaDB
   - XAMPP (hoặc tương đương)

2. **Cài đặt ứng dụng**
   ```bash
   # Clone repository
   git clone https://github.com/Siro25/Website_expense_manager.git
   cd expense-manager
   
   # Cài đặt dependencies
   composer install
   
   # Tạo file .env
   cp .env.example .env
   
   # Tạo application key
   php artisan key:generate
   ```

3. **Cấu hình database**
   - Mở file `.env` và cấu hình thông tin database:
   ```
   Đối với xampp:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=33077
   DB_DATABASE=expense_manager
   DB_USERNAME=root
   DB_PASSWORD=

   Đối với Railway(nên dùng)
   DB_CONNECTION=mysql
   DB_HOST=turntable.proxy.rlwy.net
   DB_PORT=22641
   DB_DATABASE=railway
   DB_USERNAME=root
   DB_PASSWORD=sbXBOVQdBcnzjswOmfgTAAGoTZmnuMWj
   ```

4. **Chạy migration và seeder**
   ```bash
   # Tạo các bảng trong database
   php artisan migrate
   
   # (Tùy chọn) Tạo dữ liệu mẫu
   php artisan db:seed
   ```

5. **Khởi động server**
   ```bash
   php artisan serve
   ```
### 👨‍💻 Sử dụng ứng dụng

1. **Đăng ký và đăng nhập**
   - Truy cập http://localhost:8000/register để tạo tài khoản mới
   - Hoặc đăng nhập tại http://localhost:8000/login nếu đã có tài khoản

2. **Quản lý chi tiêu**
   - Từ Dashboard, nhấp vào "Chi tiêu" trong menu bên trái
   - Để thêm chi tiêu mới, nhấp vào nút "Thêm chi tiêu"
   - Điền thông tin chi tiêu: số tiền, mô tả, ngày, danh mục
   - Để chỉnh sửa hoặc xóa, sử dụng các nút tương ứng trong danh sách

3. **Quản lý thu nhập**
   - Từ Dashboard, nhấp vào "Thu nhập" trong menu bên trái
   - Để thêm thu nhập mới, nhấp vào nút "Thêm thu nhập"
   - Điền thông tin thu nhập: số tiền, mô tả, ngày, danh mục
   - Để chỉnh sửa hoặc xóa, sử dụng các nút tương ứng trong danh sách

4. **Quản lý danh mục**
   - Từ Dashboard, nhấp vào "Danh mục chi tiêu" hoặc "Danh mục thu nhập"
   - Thêm, chỉnh sửa hoặc xóa danh mục theo nhu cầu

5. **Xem thống kê**
   - Từ Dashboard, nhấp vào "Thống kê" trong menu bên trái
   - Xem biểu đồ và báo cáo về thu chi theo thời gian
   - Lọc theo khoảng thời gian để xem dữ liệu cụ thể

6. **Quản lý tài khoản**
   - Từ Dashboard, nhấp vào "Cài đặt" trong menu bên trái
   - Cập nhật thông tin cá nhân hoặc đổi mật khẩu

## 📞 Liên hệ
📧 Email: si9x992005@gmail.com

---
Link Demo: https://youtu.be/Xlq8AyDt9Qw?si=qRzV6y_i9IHMGVXT
Link repo: https://github.com/Siro25/Website_expense_manager.git


