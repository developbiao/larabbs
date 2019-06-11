<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get facker instance
        $faker = app(Faker\Generator::class);

        // avatar fake data
        $avatars = [
            'https://image.uisdc.com/wp-content/uploads/2018/06/avatar-uisdc-chat.png',                 'https://avatars0.githubusercontent.com/u/72467?s=460&v=4',
            'https://avatars1.githubusercontent.com/u/282759?s=460&v=4',
            'https://avatars3.githubusercontent.com/u/5321787?s=200&v=4',
            'https://avatars3.githubusercontent.com/u/48630863?s=400&v=4',
            'https://avatars1.githubusercontent.com/u/1305617?s=460&v=4',
        ];

        // generator data collections
        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($user, $index) use ($faker, $avatars){
                // random avatar
                $user->avatar = $faker->randomElement($avatars);
            });

        // visible
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // insert data
        User::insert($user_array);

        // process first user
        $user = User::find(1);
        $user->name = 'GongBiao';
        $user->password = bcrypt('123456');
        $user->email = 'java770520@163.com';
        $user->avatar = 'https://avatars2.githubusercontent.com/u/4484734?s=460&v=4';
        $user->save();
        // initialization first user is founder
        $user->assignRole('Founder');

        // initialization second user is maintainer
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
