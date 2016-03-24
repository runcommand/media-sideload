runcommand/media-sideload
=========================

Sideload remote images and other media into WordPress

[![Build Status](https://travis-ci.org/runcommand/media-sideload.svg?branch=master)](https://travis-ci.org/runcommand/media-sideload)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using


~~~
wp media sideload --domain=<domain> [--post_type=<post-type>] [--verbose]
~~~

**OPTIONS**

	--domain=<domain>
		Only sideload images hosted on a specific domain.

	[--post_type=<post-type>]
		Only sideload images embedded in a specific post type.

	[--verbose]
		Show more information about the process on STDOUT.



## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install runcommand/media-sideload`

## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/runcommand/media-sideload/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.
