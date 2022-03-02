<?php

namespace App\Domain\Blackjack;

use Exception;

class Blackjack
{
    private $cards;
    private $game;

    public function __construct($cards = [], $game = [])
    {
        $this->cards = $cards;
        $this->game = $game;
        if (isset($game['winner']) && !is_null($game['winner'])) {
            throw new Exception("Jogo finalizado");
        }
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function newGame()
    {
        $this->newDeckOfCards();

        $this->getStartCardsPlayer();

        $this->getStartCardsDealer();

        $this->checkWinner();

        return [
            'game' => $this->game,
            'cards' => $this->cards,
        ];
    }

    public function getPlayerNewCard()
    {
        $card = $this->getNewCard();
        $this->game['player']['cards'][] = $card;

        $playerPoints = $this->sumPoints($this->game['player']['cards']);
        $this->game['player']['points'] = $playerPoints;

        if ($playerPoints >= 21) {
            $this->dealerTime();
            $this->checkWinner(true);
        }

        return [
            'game' => $this->game,
            'cards' => $this->cards,
        ];
    }

    public function finish()
    {
        $this->dealerTime();
        $this->checkWinner(true);

        return [
            'game' => $this->game,
            'cards' => $this->cards,
        ];
    }

    private function getStartCardsPlayer()
    {
        $playerCards = [
            $this->getNewCard(),
            $this->getNewCard(),
        ];
        $playerPoints = $this->sumPoints($playerCards);

        $this->game['player'] = [
            'cards' => $playerCards,
            'points' => $playerPoints,
        ];
    }

    private function getStartCardsDealer()
    {
        $dealerCard1 = $this->getNewCard();

        $dealerCards = [
            $dealerCard1
        ];

        $dealerCard2 = $this->getNewCard();
        if ($this->game['player']['points'] == 21 || $dealerCard1['value'] == 10 || $dealerCard1['card'] == 'A') {
            $dealerCards[] = $dealerCard2;
        }
        $dealerPoints = $this->sumPoints($dealerCards);


        $this->game['dealer'] = [
            'privateCards' => [
                $dealerCard1,
                $dealerCard2
            ],
            'cards' => $dealerCards,
            'points' => $dealerPoints,
        ];
    }

    private function dealerTime()
    {
        $dealerCards = $this->game['dealer']['privateCards'];
        $dealerPoints = $this->sumPoints($dealerCards);
        $playerPoints = $this->game['player']['points'];

        while (count($dealerCards) < 5 && $dealerPoints < 17 && $dealerPoints < $playerPoints) {
            $dealerCards[] = $this->getNewCard();
            $dealerPoints = $this->sumPoints($dealerCards);
        }

        $this->game['dealer']['cards'] = $dealerCards;
        $this->game['dealer']['points'] = $dealerPoints;
    }

    private function newDeckOfCards()
    {
        $cards = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
        $suits = ['OUROS', 'PAUS', 'COPAS', 'ESPADAS'];

        $this->cards = [];
        foreach ($suits as $suit) {
            foreach ($cards as $card) {
                $value = $card;
                if ($card == 'A') {
                    $value = 1;
                } elseif (in_array($card, ['J', 'Q', 'K'])) {
                    $value = 10;
                }
                $this->cards[] = [
                    'card' => $card,
                    'suit' => $suit,
                    'value' => (int)$value,
                ];
            }
        }
    }

    private function getNewCard()
    {
        $index = rand(0, count($this->cards) - 1);
        $card = $this->cards[$index];
        unset($this->cards[$index]);
        $this->cards = array_values($this->cards);
        return $card;
    }

    private function sumPoints($cards)
    {
        $hasCardTen = $this->checkCardIfValueIsTen($cards);
        $points = 0;
        foreach ($cards as $card) {
            $points += ($hasCardTen && $card['card'] == 'A') ? 11 : $card['value'];
        }
        return $points;
    }

    private function checkCardIfValueIsTen($cards)
    {
        foreach ($cards as $card) {
            if ($card['value'] == 10) {
                return true;
            }
        }
        return false;
    }

    private function checkWinner($finished = false)
    {
        $playerPoints = $this->game['player']['points'];
        $dealerPoints = $this->game['dealer']['points'];

        if ($playerPoints == 21 && $dealerPoints == 21) {
            $this->game['winner'] = 'Empate';
        } elseif ($playerPoints == 21 && $dealerPoints != 21) {
            $this->game['winner'] = 'Player';
        } elseif ($playerPoints != 21 && $dealerPoints == 21) {
            $this->game['winner'] = 'Dealer';
        } elseif ($playerPoints > 21) {
            $this->game['winner'] = 'Dealer';
        } elseif ($dealerPoints > 21) {
            $this->game['winner'] = 'Player';
        } elseif ($finished && $dealerPoints > $playerPoints) {
            $this->game['winner'] = 'Dealer';
        } elseif ($finished) {
            $this->game['winner'] = 'Player';
        } else {
            $this->game['winner'] = null;
        }
    }
}
