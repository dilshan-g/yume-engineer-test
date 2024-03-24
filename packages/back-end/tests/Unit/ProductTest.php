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

        /**
         * Fails the request when the product name is given empty.
         *
         * @return void
         */
        public function test_unsuccessful_response_on_product_name_is_empty(): void
        {
            $response = $this->json('POST', 'api/products', $this->empty_product_name);
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'The name field is required.',
            ]);

            $response = $this->json('PUT', 'api/products/' . $this->product->id, $this->empty_product_name);
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'The name field is required.',
            ]);
        }

        /**
         * Fails the request when the product name is less than 3 characters short.
         *
         * @return void
         */
        public function test_unsuccessful_response_on_product_name_length_too_short(): void
        {
            $response = $this->json('POST', 'api/products', $this->short_product_name);
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'The name field must be at least 3 characters.',
            ]);

            $response = $this->json('PUT', 'api/products/' . $this->product->id, $this->short_product_name);
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'The name field must be at least 3 characters.',
            ]);
        }

        /**
         * Fails the request when the product ID does not exists.
         *
         * @return void
         */
        public function test_unsuccessful_response_on_deleting_an_invalid_product_id(): void
        {
            $response = $this->json('GET', 'api/products/2');
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'The Product not found.'
            ]);

            $response = $this->json('DELETE', 'api/products/2');
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'The Product not found.'
            ]);
        }

        /**
         * Fails the request when the product ID is not a number.
         *
         * @return void
         */
        public function test_unsuccessful_response_on_product_id_is_not_a_number(): void
        {
            $response = $this->json('GET', 'api/products/2h');
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'Product ID must be an Integer.'
            ]);

            $response = $this->json('PUT', 'api/products/2h', $this->update_product_data);
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'Product ID must be an Integer.'
            ]);

            $response = $this->json('DELETE', 'api/products/2h');
            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'Product ID must be an Integer.'
            ]);
        }
    }
