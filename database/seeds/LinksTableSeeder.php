<?php

use App\Models\Link;
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
        // generator collection
        $links = factory(Link::class)->times(6)->make();

        // convert collection to array and insert data
        Link::insert($links->toArray());
    }
}
