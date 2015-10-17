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

    When player "bot-2" join last hash
    Then json response should be:
    """
    {
      "status": "ok"
    }
    """

  Scenario: check get state
    Given player "bot-1" request state last hash
    Then the response status code should be 200
    And json response should contain key "dices"
    And json response should contain key "table"

    Given player "bot-74" request state last hash
    Then the response status code should be 404
