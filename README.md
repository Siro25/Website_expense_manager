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


### 🧱 Sơ đồ lớp (Class Diagram)


```
+------------------------+       +------------------------+       +------------------------+
|         User           |       |        Category        |       |        Expense         |
+------------------------+       +------------------------+       +------------------------+
| id: int                |       | id: int                |       | id: int                |
| name: string           |       | name: string           |       | amount: decimal        |
| email: string          |       | description: string    |       | description: string    |
| password: string       |       | user_id: int           |       | date: datetime         |
| email_verified_at: date|       | created_at: timestamp  |       | user_id: int           |
| remember_token: string |       | updated_at: timestamp  |       | category_id: int       |
| created_at: timestamp  |       +------------------------+       | created_at: timestamp  |
| updated_at: timestamp  |       | +user(): BelongsTo     |       | updated_at: timestamp  |
+------------------------+       | +expenses(): HasMany   |       +------------------------+
| +expenses(): HasMany   |<------|                        |------>| +user(): BelongsTo     |
| +categories(): HasMany |       +------------------------+       | +category(): BelongsTo |
| +incomes(): HasMany    |                                        +------------------------+
| +incomeCategories():   |
|   HasMany              |
+------------------------+
          |                                                                 |
          |                                                                 |
          v                                                                 v
+------------------------+                                       +------------------------+
|    IncomeCategory      |                                       |        Income          |
+------------------------+                                       +------------------------+
| id: int                |                                       | id: int                |
| name: string           |                                       | amount: decimal        |
| user_id: int           |                                       | description: string    |
| created_at: timestamp  |                                       | date: date             |
| updated_at: timestamp  |                                       | user_id: int           |
+------------------------+                                       | income_category_id: int|
| +user(): BelongsTo     |<------------------------------------->| created_at: timestamp  |
| +incomes(): HasMany    |                                       | updated_at: timestamp  |
+------------------------+                                       +------------------------+
                                                                 | +user(): BelongsTo     |
                                                                 | +category(): BelongsTo |
                                                                 +------------------------+
```


### 🔐 Sơ đồ đăng nhập



```
+-------------------+     +-------------------+     +-------------------+
|                   |     |                   |     |                   |
|  Trang đăng nhập  |---->|  Xác thực thông   |---->|  Chuyển hướng đến |
|  (Login Form)     |     |  tin đăng nhập    |     |  trang chủ        |
|                   |     |  (Authentication) |     |  (Dashboard)      |
+-------------------+     +-------------------+     +-------------------+
| email: string     |     | +login()          |     | +index()          |
| password: string  |     | +authenticate()   |     | +getMonthlyData() |
| +submit()         |     | +validate()       |     | +getStatistics()  |
+-------------------+     +-------------------+     +-------------------+
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

## 🖥️ Yêu cầu hệ thống
- **XAMPP**
- **PHP >= 8.2**

---

## 📖 Hướng dẫn sử dụng

1. 🔐 Đăng ký hoặc đăng nhập tài khoản  
2. ➕ Thêm khoản thu/chi  
3. 📈 Xem báo cáo, thống kê  
4. ⚙️ Quản lý thông tin cá nhân và tài khoản  

---

## 🔐 Bảo mật

- ✅ Xác thực người dùng
- ✅ Phân quyền theo tài khoản
- ✅ Chống tấn công CSRF
- ✅ Mã hóa mật khẩu (bcrypt)
- ✅ Kiểm tra đầu vào
- ✅ Quản lý phiên làm việc an toàn

---

## 📬 Liên hệ

📧 Email: si9x992005@gmail.com

---

