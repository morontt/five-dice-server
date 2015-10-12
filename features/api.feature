Feature: api
  Check API functionality

  Scenario: create game
    Given call "POST" "/create" with player "bot-1"
    Then the response status code should be 200
    And json response should contain key "hash"

  Scenario: check pending games
    Given call "GET" "/pending" with player "bot-2"
    Then the response status code should be 200
    And json response should contain key "games"
    And json response should contain key "content_hash"
