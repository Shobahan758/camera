<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderSubmissionTest extends TestCase
{
    public function test_order_endpoint_rejects_invalid_data_with_bangla_messages(): void
    {
        $response = $this->postJson(route('orders.store'), [
            'name' => '',
            'phone' => '12345',
            'address' => 'ঢাকা',
            'quantity' => 0,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'আপনার নাম লিখুন।')
            ->assertJsonPath('errors.phone.0', 'সঠিক বাংলাদেশি ফোন নম্বর দিন।')
            ->assertJsonPath('errors.address.0', 'ঠিকানাটি আরও বিস্তারিতভাবে লিখুন।')
            ->assertJsonPath('errors.quantity.0', 'পরিমাণ কমপক্ষে ১ হতে হবে।');
    }

    public function test_homepage_contains_the_laravel_order_form(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('orders.store'))
            ->assertSee('name="_token"', false);
    }
}
