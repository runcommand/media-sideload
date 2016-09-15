runcommand/media-sideload
=========================

Sideload embedded images, and update post content references.

[![Build Status](https://travis-ci.org/runcommand/media-sideload.svg?branch=master)](https://travis-ci.org/runcommand/media-sideload)

Quick links: [Using](#using) | [Installing](#installing) | [Support](#support)

## Using

~~~
wp media sideload --domain=<domain> [--post_type=<post-type>] [--verbose]
~~~

Searches through the post_content field for images hosted on remote domains,
downloads those it finds into the Media Library, and updates the reference
in the post_content field.

In more real terms, this command can help "fix" all post references to
`<img src="http://remotedomain.com/image.jpg" />` by downloading the image into
the Media Library, and updating the post_content to instead use
`<img src="http://correctdomain.com/image.jpg" />`.

**OPTIONS**

	--domain=<domain>
		Specify the domain to sideload images from, because you don't want to sideload images you've already imported.

	[--post_type=<post-type>]
		Only sideload images embedded in the post_content of a specific post type.

	[--verbose]
		Show more information about the process on STDOUT.

## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install runcommand/media-sideload`.

## Support

This package is free for anyone to use. Support is available to paying [runcommand](https://runcommand.io/) customers.

Think you’ve found a bug? Before you create a new issue, you should [search existing issues](https://github.com/runcommand/sparks/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version. Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/runcommand/sparks/issues/new) with description of what you were doing, what you saw, and what you expected to see.

Want to contribute a new feature? Please first [open a new issue](https://github.com/runcommand/sparks/issues/new) to discuss whether the feature is a good fit for the project. Once you've decided to work on a pull request, please include [functional tests](https://wp-cli.org/docs/pull-requests/#functional-tests) and follow the [WordPress Coding Standards](http://make.wordpress.org/core/handbook/coding-standards/).

Github issues are meant for tracking bugs and enhancements. For general support, email [support@runcommand.io](mailto:support@runcommand.io).


