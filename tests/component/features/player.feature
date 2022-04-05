Feature: Creating and managing players

  Scenario: I want to create new unique player
    Given There is no player with name 'Bojan'
    When I send create player request with name 'Bojan'
    Then Player data with name 'Bojan' is returned
    And Player response contains token

  Scenario: I want to create new player but there is already one present with the same name
    Given There is player 'John' with id '5'
    When I send create player request with name 'John'
    Then Bad request response is returned

  Scenario: I want to get existing player by id
    Given There is player 'Bojan' with id '44'
    When I send get player request with id '44'
    Then Player data with name 'Bojan' is returned
    And Player response contains token

  Scenario: I want to get non existing player by id
    Given There is no player with id '2'
    When I send get player request with id '2'
    Then Not found response is returned