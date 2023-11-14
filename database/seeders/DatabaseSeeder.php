<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            'supreme' => ['name' => User::Supreme],
            'admin'   => ['name' => User::Admin],
            'buyer'   => ['name' => User::Buyer],
            'user'    => ['name' => User::User],
        ];

        $permissions = [
            ['name' => 'create_user'],
            ['name' => 'edit_user'],
            ['name' => 'delete_user'],
            ['name' => 'create_product'],
            ['name' => 'edit_product'],
            ['name' => 'delete_product'],
            ['name' => 'create_category'],
            ['name' => 'edit_category'],
            ['name' => 'delete_category'],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        foreach ($permissions as $permission) {
            Permission::create($permission)->syncRoles($roles['admin']);
        }

        (User::factory()->create([
            'name'     => 'Super Admin',
            'email'    => 'supreme@dev.com',
            'password' => bcrypt('password'),
        ]))->assignRole($roles['supreme']);

        (User::factory()->create([
            'name'     => 'Administrator',
            'email'    => 'admin@dev.com',
            'password' => bcrypt('password'),
        ]))->syncRoles($roles['admin']);

        $i          = 0;
        $categories = Category::pluck('id');
        while ($i < 10) {
            $user = User::create([
                'name'     => fake()->name(),
                'email'    => fake()->email(),
                'password' => bcrypt('password'),
            ])->assignRole($roles['buyer']);
            $i++;

            $j = 0;
            while ($j < 2) {
                $category_ids     = $categories->shuffle()->toArray();
                $price            = fake()->numberBetween(100, 5000);
                $discounted_price = $price - ($price * array_rand([10, 25, 50], 1)) / 100;
                $user->products()->create([
                    'name'             => fake()->sentence(),
                    'quantity'         => fake()->numberBetween(5, 25),
                    'price'            => $price,
                    'discounted_price' => $discounted_price,
                    'featured'         => fake()->boolean(),
                    'details'          => fake()->text(),
                    'added_by'         => $user->id,
                ])->categories()->sync($category_ids);
                $j++;
            }
        }

        $i = 0;
        while ($i < 10) {
            $user = User::create([
                'name'     => fake()->name(),
                'email'    => fake()->email(),
                'password' => bcrypt('password'),
            ])->assignRole($roles['user']);
            $i++;

            $comment = Product::inRandomOrder()
                ->first()
                ->comments()
                ->create([
                    'user_id' => $user->id,
                    'body'    => fake()->text(),
                ]);

            if (array_rand([0, 1], 1)) {
                $k = 0;
                while ($k < 5) {
                    $user_id = User::role($roles['user'])->inRandomOrder()->first()->id;
                    $comment->subComments()->create([
                        'user_id' => $user_id,
                        'body'    => fake()->text(),
                    ]);
                    $k++;
                }
            }
        }

    }
}
