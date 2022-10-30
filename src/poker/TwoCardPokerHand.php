<?php

namespace Poker;

require_once(__DIR__ . '/PokerHand.php');

class TwoCardPokerHand implements PokerHand
{
    private const HIGH_CARD = 'high card';
    private const PAIR = 'pair';
    private const STRAIGHT = 'straight';


    public function __construct(private array $cards)
    {
    }

    public function getHand(): string
    {
        $cardRanks = $this->getCardRanks();
        $hand = self::HIGH_CARD;
        if ($this->isStraight($cardRanks[0], $cardRanks[1])) {
            $hand = self::STRAIGHT;
        } elseif ($this->isPair($cardRanks[0], $cardRanks[1])) {
            $hand = self::PAIR;
        }

        return $hand;
    }

    private function getCardRanks(): array
    {
        return array_map(fn ($card) => $card->getCardRank(), $this->cards);
    }

    private function isStraight(int $rank1, int $rank2) : bool
    {
        if (abs($rank1 - $rank2) === 1 || $this->isMinMax($rank1, $rank2)) {
            return true;
        }
        return false;
    }

    private function isMinMax(int $rank1, int $rank2): bool
    {
        if (abs($rank1 - $rank2) === (max(PokerCard::CARDS_RANK) - min(PokerCard::CARDS_RANK))) {
            return true;
        }
        return false;
    }

    private function isPair(int $rank1, int $rank2): bool
    {
        return $rank1 === $rank2;
    }
}
