Feature: Concert

  Scenario: Get a list of concerts
    Given the database has records
    When I request "GET /api/concerts"
    Then the response status code should be 200
    And scope into the first "data" property
    And the properties exist:
      """
      id
      title
      description
      date
      """
    And the "id" property is an integer
