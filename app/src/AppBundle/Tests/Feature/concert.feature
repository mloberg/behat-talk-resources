Feature: Concerts
  In order to save upcoming concerts to my calendar
  As a vistor
  I need to be able to view upcoming concerts

  Scenario: View all upcoming concerts
    Given I am on the homepage
    When I follow "Concerts"
    Then I should see "Upcoming Concerts"

  Scenario: View concert details
    Given I am on "/concerts"
    When I click on the first link in "#concerts"
    Then I should see "Concert details"
