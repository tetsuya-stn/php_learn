<?php

namespace Poker;

require_once(__DIR__ . '/PokerHand.php');

class FiveCardPokerHand implements PokerHand
{
    private const HIGH_CARD = 'high card';
    private const ONE_PAIR = 'one pair';
    private const TWO_PAIR = 'two pair';
    private const THREE_CARD = 'three of a kind';
    private const STRAIGHT = 'straight';
    private const FULL_HOUSE = 'full house';
    private const FOUR_CARD = 'four of a kind';

    public function __construct(private array $cards)
    {
    }

    public function getHand(): string
    {
        $cardRanks = array_map(fn ($card) => $card->getCardRank(), $this->cards);
        $hand = self::HIGH_CARD;
        if ($this->isFourCard($cardRanks)) {
            $hand = self::FOUR_CARD;
        } elseif ($this->isFullHouse($cardRanks)) {
            $hand = self::FULL_HOUSE;
        } elseif ($this->isStraight($cardRanks)) {
            $hand = self::STRAIGHT;
        } elseif ($this->isThreeCard($cardRanks)) {
            $hand = self::THREE_CARD;
        } elseif ($this->isTwoPair($cardRanks)) {
            $hand = self::TWO_PAIR;
        } elseif ($this->isOnePair($cardRanks)) {
            $hand = self::ONE_PAIR;
        }

        return $hand;
    }

    private function isFourCard(array $ranks): bool
    {
        if (max(array_count_values($ranks)) === 4) {
            return true;
        }
        return false;
    }

    private function isFullHouse(array $ranks): bool
    {
        if (max(array_count_values($ranks)) === 3
        &&  min(array_count_values($ranks)) === 2) {
            return true;
        }
        return false;
    }

    private function isThreeCard(array $ranks): bool
    {
        if (max(array_count_values($ranks)) === 3
        &&  min(array_count_values($ranks)) === 1) {
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
        if ($ranks === [min(PokerCard::CARDS_RANK), min(PokerCard::CARDS_RANK) + 1, min(PokerCard::CARDS_RANK) + 2, min(PokerCard::CARDS_RANK) + 3, max(PokerCard::CARDS_RANK)]){
            return true;
        }
        return false;
    }

    private function isTwoPair(array $ranks): bool
    {
        if (max(array_count_values($ranks)) === 2
        &&  count(array_unique($ranks)) === 3) {
            return true;
        }
        return false;
    }

    private function isOnePair(array $ranks): bool
    {
        if (max(array_count_values($ranks)) === 2
        &&  count(array_unique($ranks)) === 4) {
            return true;
        }
        return false;
    }

    public function getWinner(array $cardRanks): int
    {
        return 0;
    }
}
