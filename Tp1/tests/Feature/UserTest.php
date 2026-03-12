<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/

    public function test_index_user() : void {
        $users = User::factory(4)->create();
        $response = $this->get('/api/users');
        $songs_array = $response->decodeResponseJson();

        $this->assertEquals(count($songs_array['data']), 4);

        $response->assertJsonFragment([
            'first_name' => $users[0]->first_name,
            'last_name' => $users[0]->last_name,
            'email' => $users[0]->email,
            'phone' => $users[0]->phone
        ]);

        $response->assertStatus(OK);

    }

    public function test_get_equipment_should_return_404_when_id_not_found():void{
        $this->seed();

        $response=$this->get('/api/users/696969');

        $response->assertStatus(NOT_FOUND);
    }
}
