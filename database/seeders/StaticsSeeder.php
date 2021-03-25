<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Paksuco\Statics\Models\StaticsCategory;
use Paksuco\Statics\Models\StaticsItem;

class StaticsSeeder extends Seeder
{
    private $faker = null;

    public function __construct()
    {
        $this->faker = Faker::create(app()->getLocale());
    }

    public function createCategory($order, $parent_id)
    {
        // "title", "slug", "description", "parent_id", "order"
        $category = new StaticsCategory();
        $category->title = $this->faker->company;
        $category->slug = \Illuminate\Support\Str::slug($category->title);
        $category->description = $this->faker->sentences(5, true);
        $category->parent_id = $parent_id;
        $category->order = $order;
        $category->save();
        return $category;
    }

    public function createPost($order, $category_id)
    {
        // "category_id", "title", "slug", "excerpt", "content", "published", "order", "likes", "dislikes", "visits",
        $post = new StaticsItem();
        $post->category_id = $category_id;
        $post->title = $this->faker->catchPhrase;
        $post->slug = \Illuminate\Support\Str::slug($post->title);
        $post->excerpt = $this->faker->paragraph();
        $post->content = $this->faker->paragraphs(3, true);
        $post->published = true;
        $post->order = $order;
        $post->likes = $this->faker->randomNumber(3);
        $post->dislikes = $this->faker->randomNumber(3);
        $post->visits = $this->faker->randomNumber(5);
        $post->save();
    }

    public function createCategoryWithParent($order, $parent_id = null, $number_posts = 5, $number_subcategories = 3, $max_level = 2)
    {
        $category = $this->createCategory($order, $parent_id);
        for ($p = 0; $p < $number_posts; $p++) {
            $this->createPost($p, $category->id);
        }

        if ($max_level > 0) {
            for ($c = 0; $c < $number_subcategories; $c++) {
                $this->createCategoryWithParent($c, $category->id, 5, 3, --$max_level);
            }
        }
    }

    public function run()
    {
        Schema::disableForeignKeyConstraints();
        StaticsItem::truncate();
        StaticsCategory::truncate();
        Schema::enableForeignKeyConstraints();

        for ($i = 0; $i < 5; $i++) {
            $this->createCategoryWithParent($i);
        }
    }
}
