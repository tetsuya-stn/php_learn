<?php

namespace Poker;

require_once(__DIR__ . '/PokerWinner.php');

class ThreeCardPokerWinner implements PokerWinner
{
    private const HAND_RANK = [
        'high card' => 1,
        'pair' => 2,
        'straight' => 3,
        'three card' => 4,
    ];
    public function __construct(private array $cardHands, private array $cards1, private array $cards2)
    {
    }
    public function getWinner(): int
    {
        $cardStrength = array_map(fn ($cardHand, $cards) => $this->checkHand($cardHand, $cards), $this->cardHands, [$this->cards1, $this->cards2]);

        return $this->judgeWinner($cardStrength[0], $cardStrength[1]);
    }

    private function judgeWinner(array $p1CardStrength, array $p2CardStrength): int
    {
        foreach (['rank', 'primary', 'secondary', 'thirdly'] as $key) {
            if ($p1CardStrength[$key] > $p2CardStrength[$key]) {
                return 1;
            }

            if ($p1CardStrength[$key] < $p2CardStrength[$key]) {
                return 2;
            }
        }
        return 0;
    }

    private function checkHand(string $cardHand, array $cards): array
    {
        $cardRanks = array_map(fn ($card) => $card->getCardRank(), $cards);
        $primary = max($cardRanks);
        $secondary = $this->getSecondary($cardRanks);
        $thirdly = min($cardRanks);

        if ($cardHand === 'straight' && $this->isMinMax($cardRanks)) {
            $primary = $this->getSecondary($cardRanks);
            $secondary = min($cardRanks);
            $thirdly = max($cardRanks);
        } elseif ($cardHand === 'pair' && $this->isPairNumberMin($cardRanks)) {
            $primary = min($cardRanks);
            $secondary = min($cardRanks);
            $thirdly = max($cardRanks);
        }

        return [
            'name' => $cardHand,
            'rank' => self::HAND_RANK[$cardHand],
            'primary' => $primary,
            'secondary' => $secondary,
            'thirdly' => $thirdly,
        ];
    }

    private function getSecondary(array $cardRanks): int
    {
        if (array_unique($cardRanks) === 1) {
            return min($cardRanks);
        } else {
            return current(array_diff($cardRanks, [max($cardRanks), min($cardRanks)]));
        }
    }

    private function isMinMax(array $ranks): bool
    {
        sort($ranks);
        if ($ranks === [min(PokerCard::CARDS_RANK), min(PokerCard::CARDS_RANK) + 1, max(PokerCard::CARDS_RANK)]) {
            return true;
        }
        return false;
    }

    private function isPairNumberMin(array $cardRanks): bool
    {
        if (array_count_values($cardRanks)[min($cardRanks)] === 2) {
            return true;
        }
        return false;
    }
}
