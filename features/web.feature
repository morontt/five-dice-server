Feature: web
  Check homepage and other

  Scenario: homepage
    Given I am on homepage
    Then I should see "Welcome to Five-Dice!"

  Scenario: required player header
    Given I am on "/pending"
    Then the response status code should be 401
    And json response should be:
    """
    {
      "status": "error",
      "message": "FD-PLAYER-ID header is required"
    }
    """
