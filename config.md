dùng xampp tạo database vs  "database1"

bước 1 cài composer:
    https://hoangan.net/huong-dan-cai-dat-composer-tren-windows-va-linux.html.


run terminal
- cd project
- composer install
- cp .env.example .env
điền tên database vừa tạo vào file .env ví dụ DB_DATABASE=foodweblocal
- php artisan key:generate
- php artisan migrate
- php artisan jwt:secret
- php artisan db:seed
- php artisan serve


