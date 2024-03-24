<?php

    namespace tests\Unit;

    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Laravel\Passport\Passport;
    use Tests\TestCase;
    use App\Models\Product;

    class ProductTest extends TestCase
    {
        use RefreshDatabase;

        protected object $user;

        protected object $product;

        protected array $new_product_data = [
            'name' => 'The product name',
            'description' => 'The product description',
            'price' => '99.99',
        ];

        protected array $update_product_data = [
            'name' => 'The new product name',
            'description' => 'The new Product description',
            'price' => '199.99',
        ];

        protected array $empty_product_name = [
            'name' => '',
            'description' => 'Product Description',
            'price' => '99.99',
        ];

        protected array $short_product_name = [
            'name' => 'Ne',
            'description' => 'Product Description',
            'price' => '99.99',
        ];

        public function setUp(): void
        {
            parent::setUp();

            $this->user = User::factory(User::class)->create();
            Passport::actingAs($this->user);

            $this->product = Product::factory()->create();
        }

        /**
         * Successfully retrieve a product with a valid ID.
         *
         * @return void
         */
        public function test_successful_response_on_retrieving_a_product(): void
        {
            $response = $this->json('GET', 'api/products/' . $this->product->id);
            $response->assertStatus(200);
            $response->assertJsonFragment([
                'name' => $this->product->name,
                'description' => $this->product->description,
                'price' => $this->product->price,
            ]);
        }

        /**
         * Successfully create a product with a correct payload.
         *
         * @return void
         */
        public function test_successful_response_on_creating_a_product(): void
        {
            $response = $this->json('POST', 'api/products', $this->new_product_data);
            $response->assertStatus(200);
            $response->assertJson([
                "message" => "Product created successfully.",
                "payload" => [
                    'name' => 'The product name',
                    'description' => 'The product description',
                    'price' => '99.99',
                ]
            ]);

            $this->assertDatabaseHas('products', $this->new_product_data);
        }

        /**
         * Successfully update a product with a valid ID.
         *
         * @return void
         */
        public function test_successful_response_on_updating_a_product(): void
        {
            $response = $this->json('PUT', 'api/products/' . $this->product->id, $this->update_product_data);
            $response->assertStatus(200);
            $response->assertJson([
                "message" => "The product updated successfully.",
                "payload" => [
                    'name' => 'The new product name',
                    'description' => 'The new Product description',
                    'price' => '199.99',
                ]
            ]);

            $this->assertDatabaseHas('products', $this->update_product_data);
        }

        /**
         * Successfully delete a product with a valid ID.
         *
         * @return void
         */
        public function test_successful_response_on_deleting_a_product(): void
        {
            $response = $this->json('DELETE', 'api/products/' . $this->product->id);
            $response->assertStatus(204);
            $response->assertContent('');

            $this->assertDatabaseMissing('products', ['id' => $this->product->id]);
        }

    }
