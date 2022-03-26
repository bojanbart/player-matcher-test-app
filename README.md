# Training Project Ports and Adapters Player Matcher Basic

As a player I want to create/join games to play with other people/bots.
As a developer I want to practice hexagonal architecture

## Use cases
- Player creates game and wait for others to join.
- Player joins already existing game.
- Player cancels game created by him

## Additional info
- Game starts when all game conditions are met, players should receive info about that fact.
- When creating game player can define name and number of slots for players or bots.
- Player that created a game (game creator) can assign bots to slots.
- Single player can create only one game
- player who created a game and is waiting for other to join his game cannot join other games until his game starts or is deleted