<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_input_data_order(): void
    {
        Artisan::call('db:seed --class=ProductSeeder');

        $user = User::factory()->create();

        $this->actingAs($user);

        $responseProduct = $this->get('/product');
        $product = null;
        $responseProduct->assertViewHas('products', function ($products) use (&$product) {
            if (!empty($products)) {
                $product = $products[0];
                return true;
            }
            return false;
        });

        if ($product !== null) {
            $idProduct = $product->id;
            $response = $this->post('/order', [
                'product_id' => $idProduct,
                'status' => 'Proccess',
                'quantity' => 2,
            ]);

            $response->assertViewIs('order');
            $response->assertViewHas('orders', function ($orders) use ($idProduct) {
                if ($orders->count() > 0) {
                    $resultIdProduct = $orders->first()->product_id;
                    // Check if Equal Response data order idProduct and product idProduct
                    return $resultIdProduct == $idProduct;
                }
                return false;
            });
        }
    }

    public function test_show_order_by_user(): void
    {
        Artisan::call('db:seed --class=ProductSeeder');

        $user = User::factory()->create();

        $this->actingAs($user);

        $responseProduct = $this->get('/product');
        $product = null;
        $responseProduct->assertViewHas('products', function ($products) use (&$product) {
            if (!empty($products)) {
                $product = $products[0];
                return true;
            }
            return false;
        });

        if ($product !== null) {
            $idProduct = $product->id;
            $response = $this->post('/order', [
                'product_id' => $idProduct,
                'status' => 'Proccess',
                'quantity' => 2,
            ]);

            $response->assertViewIs('order');
            $response->assertViewHas('orders', function ($orders) use ($idProduct) {
                if ($orders->count() > 0) {
                    $resultIdProduct = $orders->first()->product_id;
                    // Check if Equal Response data order idProduct and product idProduct
                    return $resultIdProduct == $idProduct;
                }
                return false;
            });


            $this->post('/order', [
                'product_id' => $idProduct,
                'status' => 'Proccess',
                'quantity' => 2,
            ]);

            $responseData = $this->get("/order");
            $responseData->assertViewIs('order');
            $responseData->assertViewHas('orders', function ($orders) {
                //Check data more than 1 after second input
                if ($orders->count() > 1) {
                    return true;
                }
                return false;
            });
        }
    }

    public function test_show_order_by_id_order(): void
    {
        Artisan::call('db:seed --class=ProductSeeder');

        $user = User::factory()->create();

        $this->actingAs($user);

        $responseProduct = $this->get('/product');
        $product = null;
        $responseProduct->assertViewHas('products', function ($products) use (&$product) {
            if (!empty($products)) {
                $product = $products[0];
                return true;
            }
            return false;
        });

        if ($product !== null) {
            $idProduct = $product->id;
            $response = $this->post('/order', [
                'product_id' => $idProduct,
                'status' => 'Proccess',
                'quantity' => 2,
            ]);

            $orderIdData = null;

            $response->assertViewIs('order');
            $response->assertViewHas('orders', function ($orders) use ($idProduct, &$orderIdData) {
                if ($orders->count() > 0) {
                    $orderIdData = $orders->first()->id;
                    return $orders->first()->product_id == $idProduct;
                }
                return false;
            });

            if ($orderIdData != null) {

                $responseData = $this->get("/order/$orderIdData");
                $responseData->assertViewIs('order');
                $responseData->assertViewHas('orders', function ($orders) use ($orderIdData, $user) {
                    $nameUserResultBool = $orders->first()->userData->name == $user->name;
                    $idOrderResultBool = $orders->first()->id == $orderIdData;
                    //Check if data user name and id order is matching on order
                    if ($nameUserResultBool && $idOrderResultBool) {
                        return true;
                    }
                    return false;
                });
            }
        }
    }

    public function test_decrement_stock_product(): void
    {
        Artisan::call('db:seed --class=ProductSeeder');

        $user = User::factory()->create();

        $this->actingAs($user);

        $responseProduct = $this->get('/product');
        $product = null;
        $responseProduct->assertViewHas('products', function ($products) use (&$product) {
            if (!empty($products)) {
                $product = $products[0];
                return true;
            }
            return false;
        });

        if ($product !== null) {
            $idProduct = $product->id;
            $this->post('/order', [
                'product_id' => $idProduct,
                'status' => 'Proccess',
                'quantity' => 2,
            ]);

            $responseDecrementProduct = $this->get('/product');
            $responseDecrementProduct->assertViewHas('products', function ($products) use (&$idProduct) {
                foreach ($products as $productData) {
                    if ($productData->id == $idProduct) {
                        //Check if Stock decrement after success add order
                        if ($productData->stock == 98) {
                            return true;
                        }
                        return false;
                    }
                }
                return false;
            });
        }
    }
}
