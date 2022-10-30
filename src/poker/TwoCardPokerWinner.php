<?php

namespace Poker;

require_once(__DIR__ . '/PokerWinner.php');
use Poker\TwoCardPokerHand;

class TwoCardPokerWinner implements PokerWinner
{
    private const HAND_RANK = [
        'high card' => 1,
        'pair' => 2,
        'straight' => 3,
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
        foreach (['rank', 'primary', 'secondary'] as $key) {
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
        $secondary = min($cardRanks);

        if ($cardHand === 'straight' && $this->isMinMax($cardRanks[0], $cardRanks[1])) {
            $primary = min($cardRanks);
            $secondary = max($cardRanks);
        }

        return [
            'name' => $cardHand,
            'rank' => self::HAND_RANK[$cardHand],
            'primary' => $primary,
            'secondary' => $secondary,
        ];
    }

    private function isMinMax(int $rank1, int $rank2): bool
    {
        if (abs($rank1 - $rank2) === (max(PokerCard::CARDS_RANK) - min(PokerCard::CARDS_RANK))) {
            return true;
        }
        return false;
    }
}
