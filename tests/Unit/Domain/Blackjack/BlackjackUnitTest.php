<?php

namespace Tests\Unit\Domain\Blackjack;

use App\Domain\Blackjack\Blackjack;
use Tests\TestCase;

class BlackjackUnitTest extends TestCase
{
    public function testCardsIsEmpty()
    {
        $blackjack = new Blackjack();
        $this->assertEquals([], $blackjack->getCards());
    }

    public function testGameIsEmpty()
    {
        $blackjack = new Blackjack();
        $this->assertEquals([], $blackjack->getGame());
    }

    public function testCreateNewDeckOfCards()
    {
        $cards = $this->getDeckOfCards();

        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('newDeckOfCards');
        $reflectionMethod->setAccessible(true);

        $blackjack = new Blackjack();
        $reflectionMethod->invoke($blackjack);

        $this->assertEquals($cards, $blackjack->getCards());
    }

    public function testCheckCardWithValueTen()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkCardIfValueIsTen');
        $reflectionMethod->setAccessible(true);

        $cards = [
            [
                'card' => 'Q',
                'suit' => 'ESPADAS',
                'value' => 10,
            ],
            [
                'card' => 'A',
                'suit' => 'ESPADAS',
                'value' => 1,
            ],
        ];

        $blackjack = new Blackjack();
        $retorno = $reflectionMethod->invokeArgs($blackjack, [$cards]);

        $this->assertTrue($retorno);
    }

    public function testCheckCardWithoutValueTen()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkCardIfValueIsTen');
        $reflectionMethod->setAccessible(true);

        $cards = [
            [
                'card' => '8',
                'suit' => 'ESPADAS',
                'value' => 8,
            ],
            [
                'card' => 'A',
                'suit' => 'ESPADAS',
                'value' => 1,
            ],
        ];

        $blackjack = new Blackjack();
        $retorno = $reflectionMethod->invokeArgs($blackjack, [$cards]);

        $this->assertFalse($retorno);
    }

    public function testSumPoints()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('sumPoints');
        $reflectionMethod->setAccessible(true);

        $cards = [
            [
                'card' => '8',
                'suit' => 'ESPADAS',
                'value' => 8,
            ],
            [
                'card' => 'A',
                'suit' => 'ESPADAS',
                'value' => 1,
            ],
        ];

        $blackjack = new Blackjack();
        $pontos = $reflectionMethod->invokeArgs($blackjack, [$cards]);

        $this->assertEquals(9, $pontos);
    }

    public function testSumPointsWithBlackjack()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('sumPoints');
        $reflectionMethod->setAccessible(true);

        $cards = [
            [
                'card' => '10',
                'suit' => 'ESPADAS',
                'value' => 10,
            ],
            [
                'card' => 'A',
                'suit' => 'ESPADAS',
                'value' => 1,
            ],
        ];

        $blackjack = new Blackjack();
        $pontos = $reflectionMethod->invokeArgs($blackjack, [$cards]);

        $this->assertEquals(21, $pontos);
    }

    public function testGetNewCard()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('getNewCard');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $blackjack = new Blackjack($cards);
        $card = $reflectionMethod->invoke($blackjack);

        $this->assertArrayHasKey('card', $card);
        $this->assertArrayHasKey('suit', $card);
        $this->assertArrayHasKey('value', $card);
    }

    public function testWinnerWhenDealerHasBlackjack()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkWinner');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $game = [];
        $game['player']['points'] = 20;
        $game['dealer']['points'] = 21;

        $blackjack = new Blackjack($cards, $game);
        $reflectionMethod->invoke($blackjack);

        $winner = $blackjack->getGame()['winner'];
        $this->assertEquals('Dealer', $winner);
    }

    public function testWinnerWhenPlayerHasBlackjack()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkWinner');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $game = [];
        $game['player']['points'] = 21;
        $game['dealer']['points'] = 20;

        $blackjack = new Blackjack($cards, $game);
        $reflectionMethod->invoke($blackjack);

        $winner = $blackjack->getGame()['winner'];
        $this->assertEquals('Player', $winner);
    }

    public function testWinnerWhenBothHasBlackjack()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkWinner');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $game = [];
        $game['player']['points'] = 21;
        $game['dealer']['points'] = 21;

        $blackjack = new Blackjack($cards, $game);
        $reflectionMethod->invoke($blackjack);

        $winner = $blackjack->getGame()['winner'];
        $this->assertEquals('Empate', $winner);
    }

    public function testWinnerWhenPlayerBust()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkWinner');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $game = [];
        $game['player']['points'] = 22;
        $game['dealer']['points'] = 5;

        $blackjack = new Blackjack($cards, $game);
        $reflectionMethod->invoke($blackjack);

        $winner = $blackjack->getGame()['winner'];
        $this->assertEquals('Dealer', $winner);
    }

    public function testWinnerWhenDealerBust()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkWinner');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $game = [];
        $game['player']['points'] = 20;
        $game['dealer']['points'] = 23;

        $blackjack = new Blackjack($cards, $game);
        $reflectionMethod->invoke($blackjack);

        $winner = $blackjack->getGame()['winner'];
        $this->assertEquals('Player', $winner);
    }

    public function testWhenDontHaveWinner()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkWinner');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $game = [];
        $game['player']['points'] = 10;
        $game['dealer']['points'] = 10;

        $blackjack = new Blackjack($cards, $game);
        $reflectionMethod->invoke($blackjack);

        $winner = $blackjack->getGame()['winner'];
        $this->assertEquals(null, $winner);
    }

    public function testWinnerWhenGameFinished()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkWinner');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $game = [];
        $game['player']['points'] = 17;
        $game['dealer']['points'] = 17;

        $blackjack = new Blackjack($cards, $game);
        $reflectionMethod->invokeArgs($blackjack, [true]);

        $winner = $blackjack->getGame()['winner'];
        $this->assertEquals('Player', $winner);
    }

    public function testWinnerWhenGameFinishedAndDealerWins()
    {
        $reflectionClass = new \ReflectionClass(Blackjack::class);
        $reflectionMethod = $reflectionClass->getMethod('checkWinner');
        $reflectionMethod->setAccessible(true);

        $cards = $this->getDeckOfCards();
        $game = [];
        $game['player']['points'] = 10;
        $game['dealer']['points'] = 11;

        $blackjack = new Blackjack($cards, $game);
        $reflectionMethod->invokeArgs($blackjack, [true]);

        $winner = $blackjack->getGame()['winner'];
        $this->assertEquals('Dealer', $winner);
    }

    public function testNewGame()
    {
        $blackjack = new Blackjack();
        $newGame = $blackjack->newGame();

        $this->assertCount(48, $newGame['cards']);
    }

    public function testGetPlayerNewCard()
    {
        $blackjack = new Blackjack();
        $newGame = $blackjack->newGame();

        $blackjack = new Blackjack($newGame['cards'], $newGame['game']);
        $game = $blackjack->getPlayerNewCard();

        $this->assertCount(3, $game['game']['player']['cards']);
    }

    private function getDeckOfCards()
    {
        $cards = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
        $suits = ['OUROS', 'PAUS', 'COPAS', 'ESPADAS'];

        $deck = [];
        foreach ($suits as $suit) {
            foreach ($cards as $card) {
                $value = $card;
                if ($card == 'A') {
                    $value = 1;
                } elseif (in_array($card, ['J', 'Q', 'K'])) {
                    $value = 10;
                }
                $deck[] = [
                    'card' => $card,
                    'suit' => $suit,
                    'value' => (int)$value,
                ];
            }
        }
        return $deck;
    }
}
