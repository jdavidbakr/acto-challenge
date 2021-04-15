<?php

namespace Tests\Feature;

use App\Models\Hand;
use App\Services\HandPlayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class HandControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_generates_a_hand_and_returns_the_result()
    {
        $service = Mockery::mock(HandPlayService::class);
        app()->instance(HandPlayService::class, $service);
        $hand = Hand::factory()->create();
        $service->shouldReceive('play')
            ->andReturn($hand);

        $response = $this->json('post', route('hand.store'), [
            'name'=>$hand->name,
            'cards'=> $hand->user_play,
        ]);

        $response->assertSuccessful();

        $response->assertJson(['data'=>$hand->toArray()]);
    }

    /**
     * @test
     */
    public function it_rejects_no_name()
    {
        $hand = Hand::factory()->create();

        $response = $this->json('post', route('hand.store'), [
            'name'=>'',
            'cards'=> $hand->user_play,
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_rejects_invalid_card_data()
    {
        $hand = Hand::factory()->create();

        $response = $this->json('post', route('hand.store'), [
            'name'=>$hand->name,
            'cards'=> 'invalid',
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_doesnt_allow_duplicate_cards()
    {
        $hand = Hand::factory()->create();

        $response = $this->json('post', route('hand.store'), [
            'name'=>$hand->name,
            'cards'=> '2 2',
        ]);

        $response->assertStatus(422);
    }
}
