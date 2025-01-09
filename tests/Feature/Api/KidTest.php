<?php

namespace Tests\Feature\Api;

use App\Models\Kid;
use Tests\TestCase;
use Database\Seeders\KidSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\CountrySeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KidTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function testCheckIfReceiveAllEntryOfKidInJsonFile(){
        $this->seed(GenderSeeder::class);
        $this->seed(CountrySeeder::class);
        $this->seed(KidSeeder::class);

        $response = $this->getJson(route('apiIndexKids'));

        $response->assertStatus(200)
            ->assertJsonCount(28);
    }
}