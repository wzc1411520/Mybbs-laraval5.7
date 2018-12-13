<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $link = factory(\App\Models\Link::class)->times(6)->make();

        \App\Models\Link::insert($link->toArray());
    }
}
