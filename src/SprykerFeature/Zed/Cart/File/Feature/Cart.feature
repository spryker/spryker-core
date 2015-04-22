@cart
Feature: Test Cart functionality
  In order to ...
  As ...
  I should ...

  @addItemToCart
  Scenario: I want to add a new product to cart
    Given an item with SKU "XYZ" and quantity 1 and price 100
    When I add item with SKU "XYZ" to cart
    Then Cart contains 1 item with SKU "XYZ"
