runcommand/media-sideload
=========================

Sideload embedded images, and update post content references.

[![Build Status](https://travis-ci.org/runcommand/media-sideload.svg?branch=master)](https://travis-ci.org/runcommand/media-sideload)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using


~~~
wp media sideload --domain=<domain> [--post_type=<post-type>] [--verbose]
~~~

Searches through the post_content field for images hosted on remote domains,
downloads those it finds into the Media Library, and updates the reference
in the post_content field.

In more real terms, this command can help "fix" all post references to
`http://remotedomain.com/image.jpg` by downloading the image into
the Media Library, and updating the post_content to instead use
`http://correctdomain.com/image.jpg`

**OPTIONS**

	--domain=<domain>
		Specify the domain to sideload images from, because you don't want to sideload images you've already imported.

	[--post_type=<post-type>]
		Only sideload images embedded in the post_content of a specific post type.

	[--verbose]
		Show more information about the process on STDOUT.



## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install runcommand/media-sideload`

## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/runcommand/media-sideload/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.
