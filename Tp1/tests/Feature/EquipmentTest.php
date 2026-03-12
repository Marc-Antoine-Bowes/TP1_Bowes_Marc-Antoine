<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Equipment;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/
    public function test_index_equipment() : void {
        $this->seed();
        $equipment = Equipment::all();
        $response = $this->get('/api/equipment');
        $equipment_array = $response->decodeResponseJson();

        $this->assertEquals(count($equipment_array['data']), 5);

        for($i=0; $i < 5; $i++){
            $response->assertJsonFragment([
                'name' => $equipment[$i]->name,
                'description' => $equipment[$i]->description,
                'daily_price' => $equipment[$i]->daily_price,
                'category_id' => $equipment[$i]->category_id
            ]);
        }

        $response->assertStatus(OK);

        for($i=0; $i < 10; $i++){
            $this->assertDatabaseHas($equipment[$i]);
        }

    }

    public function test_get_equipment_should_return_404_when_id_not_found():void{
        $this->seed();

        $response=$this->get('/api/equipment/696969');

        $response->assertStatus(NOT_FOUND);
    }

}
