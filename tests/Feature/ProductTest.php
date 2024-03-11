<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_show_product(): void
    {
        Artisan::call('db:seed --class=ProductSeeder');

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/product');
        $response->assertStatus(200);
        $response->assertViewIs('product');
        $response->assertViewHas('products', function ($products) {
            return $products->count() === 4;
        });
    }

    public function test_input_product(): void
    {
        Artisan::call('db:seed --class=ProductSeeder');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/product', [
            'name' => 'Macbook Air M3 8GB 512GB',
            'description' => 'Garansi Resmi Indonesia',
            'category' => 'Elekronik',
            'price' => 21000000,
            'stock' => 100,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Macbook Air M3 8GB 512GB',
            'description' => 'Garansi Resmi Indonesia',
            'category' => 'Elekronik',
            'price' => 21000000,
            'stock' => 100,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('product');
        $response->assertViewHas('products');
    }

    public function test_input_product_wrong_validation(): void
    {
        Artisan::call('db:seed --class=ProductSeeder');
        $user = User::factory()->create();
        $this->actingAs($user);

        $productData = [
            'name' => 'Macbook Air M1 8GB 256GB',
            'category' => 'Elekronik',
            'price' => 11000000,
            'stock' => 100,
        ];

        $validator = Validator::make($productData, [
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $this->assertTrue($validator->fails());

        $this->assertTrue($validator->errors()->has('description'));

        $response = $this->post('/product', $productData);
        $response->assertViewIs('product');
        $response->assertViewHas('errors', function ($errors) {
            return $errors instanceof \Illuminate\Support\ViewErrorBag &&
                $errors->getBag('default')->has('description') &&
                $errors->getBag('default')->first('description') === 'The description field is required.';
        });
    }

    public function test_delete_product(): void
    {
        Artisan::call('db:seed --class=ProductSeeder');
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/product');
        $product = null;
        $response->assertViewHas('products', function ($products) {
            if (!empty($products)) {
                $product = $products[0];
                return true;
            }
            return false;
        });

        if ($product !== null) {
            $this->delete("/product/$product->id");
            $this->assertDeleted('products', ['id' => $product->id]);
        }
    }

    public function test_increment_stock_product() : void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/product', [
            'name' => 'Macbook Air M3 8GB 512GB',
            'description' => 'Garansi Resmi Indonesia',
            'category' => 'Elekronik',
            'price' => 21000000,
            'stock' => 100,
        ]);
        
        $responseData = $this->get('/product');
        $product = null;
        $responseData->assertViewHas('products', function ($products) use (&$product) {
            if (!empty($products)) {
                $product = $products[0];
                if ($product->stock == 100) {
                    return true;
                }
                return false;
            }
            return false;
        });

        if ($product !== null) {
            $response = $this->put("/product/$product->id", [
                'increment' => 1
            ]);
            $response->assertViewHas('products', function ($products) {
                if (!empty($products)) {
                    $product = $products[0];
                    if ($product->stock == 101) {
                        return true;
                    }
                    return false;
                }
                return false;
            });
        }
    }

    public function test_decrement_stock_product() : void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/product', [
            'name' => 'Macbook Air M3 8GB 512GB',
            'description' => 'Garansi Resmi Indonesia',
            'category' => 'Elekronik',
            'price' => 21000000,
            'stock' => 100,
        ]);
        
        $responseData = $this->get('/product');
        $product = null;
        $responseData->assertViewHas('products', function ($products) use (&$product) {
            if (!empty($products)) {
                $product = $products[0];
                if ($product->stock == 100) {
                    return true;
                }
                return false;
            }
            return false;
        });

        if ($product !== null) {
            $response = $this->put("/product/$product->id", [
                'decrement' => 1
            ]);
            $response->assertViewHas('products', function ($products) {
                if (!empty($products)) {
                    $product = $products[0];
                    if ($product->stock == 99) {
                        return true;
                    }
                    return false;
                }
                return false;
            });
        }
    }

    
}
