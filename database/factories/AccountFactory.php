<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        $names = [
            'بيبي هاوس',
            'المخزن الالماني للبالة',
            'NM4shopping',
            'صيانة شارع الرشيد',
            'ملبوسات جاندا جان',
            'mustafa iszim',
            'محلات الصين',
            'ازياء علاوي الباش',
            'البشير اون لاين',
            'محل الهلال',
            'دكان المنصور',
            'هير كرومينك',
            'احذية بودو',
            'العربية',
            'اوروك',
            'حمودي شورجة',
            'كوزمتك بنوتات',
            'مركز سلم',
            'مجمع الصبايا /المواد المنزلية',
            'noor fashion',
            'ست البيت',
            'ديما للتسوق الالكتروني',
            'ارض الخليج',
            'ارخص الاسعار للملابس',
            'معرض كناري',
            'احذية المنصور',];

        return [
            'name' => $this->faker->unique()->words(2, true),
            'phone' => '791' . fake()->unique()->numberBetween(1111111, 9999999),
        ];
    }
}
