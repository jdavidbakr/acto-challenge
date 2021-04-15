<?php

namespace App\Services;

use App\Models\Hand;

class HandPlayService
{
    public function play($name, $cards)
    {
        $cards = collect(explode(" ", $cards));
        $computer_cards = $this->generateComputerHand($cards->count());
        $scores = collect(config('cards'))->flip()
            ->transform(function ($score) {
                return $score + 1;
            });
        $hand = new Hand([
            'name'=>$name,
            'user_play'=>$cards->implode(" "),
            'computer_play'=>$computer_cards->implode(" "),
            'user_score'=>0,
            'computer_score'=>0,
        ]);
        $cards->each(function ($card, $index) use ($computer_cards, $scores, &$hand) {
            if ($scores->get($card) > $scores->get($computer_cards[$index])) {
                $hand->user_score++;
            } elseif ($scores->get($card) < $scores->get($computer_cards[$index])) {
                $hand->computer_score++;
            }
        });
        $hand->user_won = $hand->user_score > $hand->computer_score;
        $hand->save();
        return $hand;
    }

    protected function generateComputerHand($cards)
    {
        return collect(config('cards'))
            ->shuffle()
            ->forpage(1, $cards);
    }
}
