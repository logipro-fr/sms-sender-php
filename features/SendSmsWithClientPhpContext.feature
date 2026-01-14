# language: en
Feature: Sending SMS via the client

  Scenario: Successful SMS sending
    Given I have a valid phone number "+33612345678"
    Given I have a message "Message de test via SDK PHP"
    When I send the SMS
    Then a message ID should be returned