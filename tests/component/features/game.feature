Feature: Creating and managing games

  Scenario: I want to create new unique game
    Given There is no game with name 'Game 1'
    And I am identified as player with id '3'
    And There is no active games created by player '3'
    When I send create game request with name 'Game 1' and slots '3'
    Then Game data with name 'Game 1' and slots '3' is returned

  Scenario: I want to create new game but there is already one present with the same name
    Given There is game 'Game S' with '2' slots and id '1' created by player '2'
    And I am identified as player with id '3'
    And There is no active games created by player '3'
    When I send create game request with name 'Game S' and slots '8'
    Then Bad request response is returned

  Scenario: I want to create new game but I have at least one active game
    Given There is no game with name 'Game M'
    And I am identified as player with id '5'
    And There is active game created by player '5'
    When I send create game request with name 'Game M' and slots '4'
    Then Bad request response is returned

  Scenario: I want to get game by id
    Given There is player 'Bojan' with id '2'
    And There is game 'Game S' with '2' slots and id '1' created by player '2'
    And I am identified as player with id '4'
    When I send get game request with id '1'
    Then Game data with name 'Game S' and slots '2' is returned
    And Player 'Bojan' is present on opponents list

  Scenario: I want to get non existing game by id
    Given There is no game with id '3'
    And I am not identified
    When I send get game request with id '3'
    Then Not found response is returned

  Scenario: I want to get list of available games
    Given There is game 'Game S' with '2' slots and id '1' created by player '2'
    And There is game 'Game L' with '5' slots and id '5' created by player '3'
    And I am not identified
    When I send get game list request
    Then Game list is returned containing
    And Game 'Game S' is present on result list
    And Game 'Game L' is present on result list

  Scenario: I want to remove game created by me
    Given There is game 'Game S' with '2' slots and id '1' created by player '2'
    And I am identified as player with id '2'
    When I send delete game request with id '1'
    Then Game with id '1' should be removed

  Scenario: I want to remove game created by other player
    Given There is game 'Game S' with '2' slots and id '1' created by player '2'
    And I am identified as player with id '3'
    When I send delete game request with id '1'
    Then Forbidden response is returned

  Scenario: I want to assign myself to game
    Given There is player 'Bojan' with id '3'
    And There is game 'Game S' with '2' slots and id '1' created by player '2'
    And I am identified as player with id '3'
    When I send update game '1' request with player opponent 'Bojan'
    Then Game data with name 'Game S' and slots '2' is returned
    And Player 'Bojan' is present on opponents list

  Scenario: I want to assign ai bot to game
    Given There is player 'Bojan' with id '3'
    And There is game 'Game S' with '2' slots and id '1' created by player '2'
    And I am identified as player with id '3'
    When I send update game '1' request with ai opponent 'strong'
    Then Game data with name 'Game S' and slots '2' is returned
    And Ai bot 'strong' is present on opponents list

  Scenario: I want to assign myself to game twice
    Given There is player 'Bojan' with id '3'
    And There is game 'Game S' with '3' slots and id '1' created by player '2'
    And Game '1' has assigned player '3'
    And I am identified as player with id '3'
    When I send update game '1' request with player opponent 'Bojan'
    Then Bad request response is returned

  Scenario: I want to assign myself to game but all slots are occupied
    Given There is player 'Bojan' with id '4'
    And There is game 'Game S' with '2' slots and id '1' created by player '2'
    And Game '1' has assigned player '3'
    And I am identified as player with id '4'
    When I send update game '1' request with player opponent 'Bojan'
    Then Bad request response is returned