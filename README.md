## Sobre a API

API em Laravel para o Jogo Blackjack

## Como usar

- Faça um clone do projeto
- Execute o comando ``composer install``
- Configure o arquivo .env e informe uma base de dados
- Execute o comando ``php artisan migrate``

## Rotas da API

### Novo jogo
Esta rota retorna as cartas do Dealer e Player, além de um ``game_id`` para interagir com esse jogo.
- /api/new-game

### Nova carta para o jogador
Adiciona uma nova carta para o jogar. Se o jogador exceder 21 pontos passa automaticamente para a vez do Dealer. O ``game_id`` deve ser informado.
- /api/new-card/{game_id}

### Encerrar o jogo
O jogador encerra e passa a vez para o Dealer.
- /api/finish-game/{game_id}
