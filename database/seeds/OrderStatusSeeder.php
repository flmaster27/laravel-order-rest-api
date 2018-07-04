<?php

use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Заполняем статусы
        $data = array(
            array('name' => 'Новый'),
            array('name' => 'Ожидает оплаты'),
            array('name' => 'Оплачен'),
        );

        DB::table('order_statuses')->insert($data);
    }
}
