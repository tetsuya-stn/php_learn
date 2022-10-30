<?php

namespace Poker;

require_once(__DIR__ . '/PokerHand.php');

class ThreeCardPokerHand implements PokerHand
{
    private const HIGH_CARD = 'high card';
    private const PAIR = 'pair';
    private const STRAIGHT = 'straight';
    private const THREE_CARD = 'three card';

    public function __construct(private array $cards)
    {
    }

    public function getHand(): string
    {
        $cardRanks = $this->getCardRanks($this->cards);
        $hand = self::HIGH_CARD;
        if ($this->isThreeCard($cardRanks)) {
            $hand = self::THREE_CARD;
        } elseif ($this->isStraight($cardRanks)) {
            $hand = self::STRAIGHT;
        } elseif ($this->isPair($cardRanks)) {
            $hand = self::PAIR;
        }

        return $hand;
    }

    private function getCardRanks(array $cards): array
    {
        return array_map(fn ($card) => $card->getCardRank(), $this->cards);
    }

    private function isThreeCard(array $ranks): bool
    {
        if (count(array_unique($ranks)) === 1) {
            return true;
        }
        return false;
    }

    private function isStraight(array $ranks) : bool
    {
        sort($ranks);
        if (range(min($ranks), min($ranks) + count($ranks) - 1) === $ranks || $this->isMinMax($ranks)) {
            return true;
        }
        return false;
    }

    private function isMinMax(array $ranks): bool
    {
        if ($ranks === [min(PokerCard::CARDS_RANK), min(PokerCard::CARDS_RANK) + 1, max(PokerCard::CARDS_RANK)]){
            return true;
        }
        return false;
    }

    private function isPair(array $ranks): bool
    {
        return count(array_unique($ranks)) === 2;
    }
}
