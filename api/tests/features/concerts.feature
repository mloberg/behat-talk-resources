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

  Scenario: Create a concert
    Given the database has records
    And I have the payload:
      """
      {
        "title": "Super Awesome Concert",
        "description": "Super Awesome Concert with Super Awesome Bands",
        "date": "2015-08-01 17:00:00"
      }
      """
    When I request "POST /api/concerts"
    Then the response status code should be 201
    And the properties exist:
      """
      title
      description
      date
      """
    And the "title" property equals "Super Awesome Concert"
