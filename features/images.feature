Feature: Sideloading Images

  Scenario: Sideload images in post content from a remote domain
    Given a WP install

    When I run `wp post create --post_title='Post with WordPress.org image' --post_content=' This is a post with an image from WordPress.org <img src="http://s.wordpress.org/style/images/codeispoetry.png" />'`
    Then STDOUT should not be empty

    When I run `wp post list --s='s.wordpress.org' --format=count`
    Then STDOUT should be:
      """
      1
      """

    When I run `wp media sideload --domain='s.wordpress.org'`
    Then STDERR should be empty

    When I run `wp post list --s='s.wordpress.org' --format=count`
    Then STDOUT should be:
      """
      0
      """
