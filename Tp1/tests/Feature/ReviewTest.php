<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Review;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/

public function test_destroy_deletes_review()
{
    //Avec l'aide de l'ia  prompt : comment faire un test sur un delete en laravel
    $review = Review::factory()->create();

    $response = $this->deleteJson("/api/reviews/{$review->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('reviews', [
        'id' => $review->id
    ]);
}
}
