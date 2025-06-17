# Ứng dụng Quản lý Chi tiêu (Expense Manager)

## Thông tin sinh viên
- Họ và tên: Đỗ Tiến Sĩ
- MSSV: 23010577
- Lớp: K17-CNTT6
- Môn học: Thiết kế Web nâng cao (TH3)

## Giới thiệu dự án
Ứng dụng Quản lý Chi tiêu (Expense Manager) là một hệ thống web được phát triển để giúp người dùng theo dõi, quản lý và phân tích các khoản thu chi cá nhân một cách hiệu quả. Ứng dụng cung cấp giao diện trực quan, dễ sử dụng, cho phép người dùng ghi lại các khoản chi tiêu, thu nhập, phân loại theo danh mục và xem báo cáo thống kê chi tiết.

Các tính năng chính của ứng dụng bao gồm:
- Quản lý chi tiêu: Thêm, sửa, xóa các khoản chi tiêu
- Quản lý thu nhập: Theo dõi các nguồn thu nhập
- Phân loại danh mục: Tổ chức chi tiêu và thu nhập theo danh mục
- Báo cáo thống kê: Biểu đồ trực quan về tình hình tài chính
- Bảng điều khiển tổng quan: Xem nhanh tình hình tài chính hiện tại

## Công nghệ sử dụng

### Backend
- **PHP 8.2**: Ngôn ngữ lập trình phía máy chủ
- **Laravel 12**: Framework PHP hiện đại với nhiều tính năng
- **MySQL**: Hệ quản trị cơ sở dữ liệu quan hệ
- **Eloquent ORM**: Tương tác với cơ sở dữ liệu thông qua mô hình đối tượng

### Frontend
- **Blade Template Engine**: Công cụ tạo giao diện của Laravel
- **TailwindCSS**: Framework CSS xây dựng giao diện người dùng
- **JavaScript/Alpine.js**: Tương tác động trên trang web
- **Chart.js**: Thư viện tạo biểu đồ trực quan

### Công cụ phát triển
- **Composer**: Quản lý các gói phụ thuộc PHP
- **Git**: Hệ thống quản lý phiên bản
- **XAMPP**: Môi trường phát triển tích hợp Apache, MySQL, PHP

## Sơ đồ hệ thống

### Sơ đồ khối

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

### Sơ đồ lớp (Class Diagram)

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

### Sơ đồ đăng nhập

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

### Sơ đồ CRUD (Create, Read, Update, Delete)

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

## Yêu cầu hệ thống
- XAMPP (Apache, MySQL)
- PHP >= 8.2

## Hướng dẫn sử dụng
1. Đăng ký tài khoản mới hoặc đăng nhập nếu đã có tài khoản
2. Thêm các khoản thu chi trong phần "Thêm giao dịch mới"
3. Xem báo cáo và thống kê trong phần "Báo cáo"
4. Có thể chỉnh sửa thông tin và xóa tài khoản trong phần "Cài đặt"

## Bảo mật
Ứng dụng Quản lý Chi tiêu được xây dựng với các tính năng bảo mật sau:

1. **Xác thực người dùng**: Hệ thống đăng nhập/đăng ký an toàn với mật khẩu được mã hóa
2. **Phân quyền**: Mỗi người dùng chỉ có thể truy cập và quản lý dữ liệu của chính mình
3. **Bảo vệ CSRF**: Chống tấn công giả mạo yêu cầu từ các trang khác
4. **Mã hóa dữ liệu**: Mật khẩu được mã hóa bằng thuật toán bcrypt
5. **Xác thực đầu vào**: Kiểm tra và làm sạch dữ liệu người dùng nhập vào
6. **Phiên làm việc an toàn**: Quản lý phiên làm việc với cơ chế bảo mật

## Liên hệ
Nếu bạn có bất kỳ câu hỏi hoặc góp ý nào, vui lòng liên hệ:
Email: si9x992005@gmail.com
#   W e b s i t e _ Q u a n - L y _ c h i - t i e u - e x p e n s e _ m a n a g e r - e x p e n s e _ m  
 #   W e b s i t e _ Q u a n - L y _ c h i - t i e u - e x p e n s e _ m a n a g e r - e x p e n s e _ m  
 