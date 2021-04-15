<?php

namespace Tests\Feature;

use App\Services\HandPlayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HandPlayServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_generates_the_computer_hand_and_stores_the_result()
    {
        $play = new HandPlayService();
        
        $hand = $play->play("Name", "2 3 4 5");

        $this->assertDatabaseHas('hands', [
            'id'=>$hand->id,
            'name'=>'Name',
            'user_play'=>'2 3 4 5',
        ]);
        if ($hand->user_score > $hand->computer_score) {
            $this->assertTrue($hand->user_won);
        } else {
            $this->assertFalse($hand->user_won);
        }
    }
}
