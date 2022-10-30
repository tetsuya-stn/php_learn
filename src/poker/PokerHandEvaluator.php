<?php

namespace Poker;

class PokerHandEvaluator
{
    public function __construct()
    {
    }

    public function getHand(PokerHand $pokerHand)
    {
        return $pokerHand->getHand();
    }

    public function getWinner(PokerWinner $pokerWinner)
    {
        return $pokerWinner->getWinner();
    }
}
