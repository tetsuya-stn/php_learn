<?php

namespace Poker;

class PokerQuiz
{
    public function __construct(private array $cards1, private array $cards2)
    {
    }

    public function start(): array
    {
        $cardHands = [];
        foreach ([$this->cards1, $this->cards2] as $cards) {
            $pokerCards = $this->getPokerCard($cards);
            $rule = $this->getNumberOfCards($pokerCards);
            $handEvaluator = new PokerHandEvaluator();
            $cardHands[] = $handEvaluator->getHand($rule);
        }
        $winnerRule = $this->getWinnerRule($pokerCards, $cardHands, $this->getPokerCard($this->cards1), $this->getPokerCard($this->cards2));
        $winner = $handEvaluator->getWinner($winnerRule);
        return [$cardHands[0], $cardHands[1], $winner];
    }

    private function getPokerCard(array $cards): array
    {
        return array_map(fn ($card) => new PokerCard($card), $cards);
    }

    private function getNumberOfCards(array $pokerCards)
    {
        $rule = new TwoCardPokerHand($pokerCards);
        if (count($pokerCards) === 3) {
            $rule = new ThreeCardPokerHand($pokerCards);
        }

        if (count($pokerCards) === 5) {
            $rule = new FiveCardPokerHand($pokerCards);
        }
        return $rule;
    }

    private function getWinnerRule(array $pokerCards, array $cardHands, array $cards1, array $cards2)
    {
        $rule = new TwoCardPokerWinner($cardHands, $cards1, $cards2);
        if (count($pokerCards) === 3) {
            $rule = new ThreeCardPokerWinner($cardHands, $cards1, $cards2);
        }
        return $rule;
    }
}
