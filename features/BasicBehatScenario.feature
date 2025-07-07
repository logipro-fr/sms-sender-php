# language: en
Feature: Behat is working

    Scenario: Behat works
    Given SmsClient is installed
    When I run Behat
    Then This Scenario success
