<?php

namespace Tests\Feature\Catalog;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_authenticated_users_can_visit_category_and_product_pages(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::create(['name' => 'Electronics', 'status' => true]);
        $product = Product::create([
            'name' => 'Headphones',
            'category_id' => $category->id,
            'price' => 49.99,
            'description' => 'Wireless headphones.',
            'status' => true,
        ]);

        $this->get(route('categories.index'))->assertOk();
        $this->get(route('categories.create'))->assertOk();
        $this->get(route('categories.edit', $category))->assertOk();
        $this->get(route('products.index'))->assertOk();
        $this->get(route('products.create'))->assertOk();
        $this->get(route('products.edit', $product))->assertOk();
    }

    public function test_category_and_product_listings_are_paginated_by_ten(): void
    {
        $this->actingAs(User::factory()->create());
        $category = Category::create(['name' => 'Category 1', 'status' => true]);

        for ($index = 2; $index <= 11; $index++) {
            Category::create(['name' => "Category {$index}", 'status' => true]);
        }

        for ($index = 1; $index <= 11; $index++) {
            Product::create([
                'name' => "Product {$index}",
                'category_id' => $category->id,
                'price' => $index,
                'description' => "Description {$index}.",
                'status' => true,
            ]);
        }

        $this->get(route('categories.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('"current_page":2', false)
            ->assertSee('"per_page":10', false)
            ->assertSee('"total":11', false);

        $this->get(route('products.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('"current_page":2', false)
            ->assertSee('"per_page":10', false)
            ->assertSee('"total":11', false);
    }

    public function test_authenticated_users_can_create_update_and_delete_categories(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('categories.store'), [
            'name' => 'Office',
            'status' => true,
        ])->assertRedirect(route('categories.index'));

        $category = Category::where('name', 'Office')->firstOrFail();
        $this->assertTrue($category->status);

        $this->put(route('categories.update', $category), [
            'name' => 'Office Supplies',
            'status' => false,
        ])->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Office Supplies',
            'status' => false,
        ]);

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_authenticated_users_can_create_update_and_delete_products(): void
    {
        $this->actingAs(User::factory()->create());
        $category = Category::create(['name' => 'Electronics', 'status' => true]);
        $productData = [
            'name' => 'Wireless Headphones',
            'category_id' => $category->id,
            'price' => 79.99,
            'description' => 'Comfortable wireless headphones.',
            'status' => true,
        ];

        $this->post(route('products.store'), $productData)
            ->assertRedirect(route('products.index'));

        $product = Product::where('name', 'Wireless Headphones')->firstOrFail();
        $this->assertSame($category->id, $product->category_id);
        $this->assertSame(79.99, $product->price);

        $this->put(route('products.update', $product), [
            ...$productData,
            'name' => 'Updated Headphones',
            'price' => 89.50,
            'status' => false,
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Headphones',
            'price' => 89.5,
            'status' => false,
        ]);

        $this->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_products_require_a_valid_category_and_categories_with_products_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $category = Category::create(['name' => 'Books', 'status' => true]);

        $this->from(route('products.create'))
            ->post(route('products.store'), [
                'name' => 'A Book',
                'category_id' => 999,
                'price' => 10,
                'description' => 'A description.',
                'status' => true,
            ])
            ->assertRedirect(route('products.create'))
            ->assertSessionHasErrors('category_id');

        Product::create([
            'name' => 'A Book',
            'category_id' => $category->id,
            'price' => 10,
            'description' => 'A description.',
            'status' => true,
        ]);

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_database_seeder_creates_ten_categories_and_products(): void
    {
        $this->seed();

        $this->assertDatabaseCount('categories', 10);
        $this->assertDatabaseCount('products', 10);
    }
}
