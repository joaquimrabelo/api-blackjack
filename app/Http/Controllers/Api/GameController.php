<?php

namespace App\Http\Controllers\Api;

use App\Domain\Blackjack\Blackjack;
use App\Http\Controllers\Controller;
use App\Models\Game;
use Exception;

class GameController extends Controller
{
    public function newGame(Blackjack $blackjack)
    {
        $newGame = $blackjack->newGame();

        $game = new Game();
        $game->cards = json_encode($newGame['cards']);
        $game->game = json_encode($newGame['game']);
        $game->save();

        unset($newGame['game']['dealer']['privateCards']);
        return [
            'game' => $newGame['game'],
            'game_id' => $game->id,
        ];
    }

    public function newCard(Game $game)
    {
        if (!$game->exists) {
            throw new Exception('Informe o ID do game');
        }

        $cards = json_decode($game->cards, true);
        $savedGame = json_decode($game->game, true);

        $blackjack = new Blackjack($cards, $savedGame);
        $savedGame = $blackjack->getPlayerNewCard($savedGame);

        $game->cards = json_encode($savedGame['cards']);
        $game->game = json_encode($savedGame['game']);
        $game->save();

        unset($savedGame['game']['dealer']['privateCards']);
        return [
            'game' => $savedGame['game'],
            'game_id' => $game->id,
        ];
    }

    public function finish(Game $game)
    {
        if (!$game->exists) {
            throw new Exception('Informe o ID do game');
        }

        $cards = json_decode($game->cards, true);
        $savedGame = json_decode($game->game, true);

        $blackjack = new Blackjack($cards, $savedGame);
        $savedGame = $blackjack->finish($savedGame);

        $game->cards = json_encode($savedGame['cards']);
        $game->game = json_encode($savedGame['game']);
        $game->save();

        unset($savedGame['game']['dealer']['privateCards']);
        return [
            'game' => $savedGame['game'],
            'game_id' => $game->id,
        ];
    }
}
