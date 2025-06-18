<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ăn uống',
                'description' => 'Chi phí ăn uống, nhà hàng, café',
            ],
            [
                'name' => 'Di chuyển',
                'description' => 'Chi phí xăng xe, taxi, xe buýt',
            ],
            [
                'name' => 'Giải trí',
                'description' => 'Chi phí giải trí, xem phim, du lịch',
            ],
            [
                'name' => 'Mua sắm',
                'description' => 'Chi phí mua sắm quần áo, đồ dùng',
            ],
            [
                'name' => 'Hóa đơn',
                'description' => 'Chi phí điện, nước, internet',
            ],
            [
                'name' => 'Sức khỏe',
                'description' => 'Chi phí khám bệnh, thuốc men',
            ],
            [
                'name' => 'Giáo dục',
                'description' => 'Chi phí học tập, sách vở',
            ],
            [
                'name' => 'Khác',
                'description' => 'Các chi phí khác',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
