<?php

namespace Poker;

require_once(__DIR__ . '/PokerCard.php');

interface PokerHand
{
    public function getHand();
}
