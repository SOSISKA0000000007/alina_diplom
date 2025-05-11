<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Price;
use Illuminate\Database\Seeder;

class ToursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tours = [
            [
                'title' => 'Баржа',
                'description' => 'Построенное в 1907 году, попав в 1945 году под обстрел советской артиллерии, корабль затонул у берегов г. Балтийска. Длина – 58,8 м, ширина – 9м. Количество человек в группе: 2-6',
                'image' => 'images/tours/barge.jpg',
                'dates' => [
                    ['start_date' => '2024-05-01', 'end_date' => '2024-05-07'],
                    ['start_date' => '2024-06-01', 'end_date' => '2024-06-07'],
                ],
                'prices' => [
                    ['regular_price' => 15000, 'sale_price' => 13500],
                ]
            ],
            [
                'title' => 'Драхе',
                'description' => 'Построенное в 1907 году, попав в 1945 году под обстрел советской артиллерии, корабль затонул у берегов г. Балтийска. Длина – 58,8 м, ширина – 9м. Количество человек в группе: 2-6',
                'image' => 'images/tours/drache.jpg',
                'dates' => [
                    ['start_date' => '2024-05-15', 'end_date' => '2024-05-21'],
                    ['start_date' => '2024-06-15', 'end_date' => '2024-06-21'],
                ],
                'prices' => [
                    ['regular_price' => 16000, 'sale_price' => 14400],
                ]
            ],
            [
                'title' => 'Балхаш',
                'description' => 'Затонул у берегов г. Балтийска в 2004 году во время сильного шторма. Глубина залегания – 19 м. Видимость в зависимости от погоды составляет 2-10 м. Количество человек в группе: 2-6',
                'image' => 'images/tours/balkhash.jpg',
                'dates' => [
                    ['start_date' => '2024-05-10', 'end_date' => '2024-05-16'],
                    ['start_date' => '2024-06-10', 'end_date' => '2024-06-16'],
                ],
                'prices' => [
                    ['regular_price' => 14000, 'sale_price' => 12600],
                ]
            ],
            [
                'title' => 'Ледокол Поллукус',
                'description' => 'Немецкое судно затонуло на глубине 13 м. в 1945 г., попав на мину. Можно попасть во многие помещения. Под затонувшими частями ледокола можно найти экземпляры янтаря. Количество человек в группе: 2-6',
                'image' => 'images/tours/pollux.jpg',
                'dates' => [
                    ['start_date' => '2024-05-20', 'end_date' => '2024-05-26'],
                    ['start_date' => '2024-06-20', 'end_date' => '2024-06-26'],
                ],
                'prices' => [
                    ['regular_price' => 17000, 'sale_price' => 15300],
                ]
            ],
            [
                'title' => 'Ледокол СКР «Барсук»',
                'description' => 'Глубина залегания – 45 метров. Размеры судна можно сравнить с футбольным полем. Построенный в 1952 году, СКР «Барсук» сопровождал конвой и охранял Балтийское море. Количество человек в группе: 2-6',
                'image' => 'images/tours/barsuk.jpg',
                'dates' => [
                    ['start_date' => '2024-05-25', 'end_date' => '2024-05-31'],
                    ['start_date' => '2024-06-25', 'end_date' => '2024-06-30'],
                ],
                'prices' => [
                    ['regular_price' => 18000, 'sale_price' => 16200],
                ]
            ],
        ];

        foreach ($tours as $tourData) {
            $tour = Tour::create([
                'title' => $tourData['title'],
                'description' => $tourData['description'],
                'image' => $tourData['image'],
            ]);

            foreach ($tourData['dates'] as $date) {
                TourDate::create([
                    'tour_id' => $tour->id,
                    'start_date' => $date['start_date'],
                    'end_date' => $date['end_date'],
                ]);
            }

            foreach ($tourData['prices'] as $price) {
                Price::create([
                    'tour_id' => $tour->id,
                    'regular_price' => $price['regular_price'],
                    'sale_price' => $price['sale_price'],
                ]);
            }
        }
    }
}
