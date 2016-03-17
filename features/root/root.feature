Feature: Root
    In order to send a get request to root endingpoint and get welcome response
    As a user not logged in
    I need to be able to send a get request to root endingpoint and get welcome response

    Scenario: Sent GET request to root endingpoint [/] and get welcome response
        Given I set header "Content-Type" with value "text/plain"
        When I send a GET request to "/"
        Then the response code should be 200
        And the response should contain json:
        """
            {
                "data": "Welcome"
            }
        """
