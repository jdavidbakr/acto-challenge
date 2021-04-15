<?php

namespace App\Services;

use App\Models\Hand;

class HandPlayService
{
    public function play($name, $cards)
    {
        $cards = collect(explode(" ", $cards));
        $computer_cards = $this->generateComputerHand($cards->count());
        return $this->makeHandModel($name, $cards, $computer_cards);
    }

    protected function generateComputerHand($cards)
    {
        return collect(config('cards'))
            ->shuffle()
            ->forpage(1, $cards);
    }

    protected function makeHandModel($name, $cards, $computer_cards)
    {
        $hand = $this->initializeHand($name, $cards, $computer_cards);
        $this->scoreHand($hand, $cards, $computer_cards);
        $this->evaluateWin($hand);
        $hand->save();
        return $hand;
    }

    protected function initializeHand($name, $cards, $computer_cards)
    {
        return new Hand([
            'name'=>$name,
            'user_play'=>$cards->implode(" "),
            'computer_play'=>$computer_cards->implode(" "),
            'user_score'=>0,
            'computer_score'=>0,
        ]);
    }

    public function scoreHand($hand, $cards, $computer_cards)
    {
        $scores = $this->getCardScores();
        $cards->each(function ($card, $index) use ($computer_cards, $scores, &$hand) {
            if ($scores->get($card) > $scores->get($computer_cards[$index])) {
                $hand->user_score++;
            } elseif ($scores->get($card) < $scores->get($computer_cards[$index])) {
                $hand->computer_score++;
            }
        });
    }

    protected function evaluateWin($hand)
    {
        $hand->user_won = $hand->user_score > $hand->computer_score;
    }

    protected function getCardScores()
    {
        return collect(config('cards'))->flip()
            ->transform(function ($score) {
                return $score + 1;
            });
    }
}
