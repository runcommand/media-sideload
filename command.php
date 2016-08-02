<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Sideload embedded images, and update post content references.
 *
 * Searches through the post_content field for images hosted on remote domains,
 * downloads those it finds into the Media Library, and updates the reference
 * in the post_content field.
 *
 * In more real terms, this command can help "fix" all post references to
 * `<img src="http://remotedomain.com/image.jpg" />` by downloading the image into
 * the Media Library, and updating the post_content to instead use
 * `<img src="http://correctdomain.com/image.jpg" />`.
 *
 * ## OPTIONS
 *
 * --domain=<domain>
 * : Specify the domain to sideload images from, because you don't want to sideload images you've already imported.
 *
 * [--post_type=<post-type>]
 * : Only sideload images embedded in the post_content of a specific post type.
 *
 * [--verbose]
 * : Show more information about the process on STDOUT.
 */
$run_sideload_media_command = function( $args, $assoc_args ) {
	global $wpdb;

	$defaults = array(
		'domain'      => '',
		'post_type'   => '',
		'verbose'     => false,
		);
	$assoc_args = array_merge( $defaults, $assoc_args );

	$where_parts = array();

	$domain_str = '%' . esc_url_raw( $assoc_args['domain'] ) . '%';
	$where_parts[] = $wpdb->prepare( "post_content LIKE %s", $domain_str );

	if ( ! empty( $assoc_args['post_type'] ) ) {
		$where_parts[] = $wpdb->prepare( "post_type = %s", sanitize_key( $assoc_args['post_type'] ) );
	} else {
		$where_parts[] = "post_type NOT IN ('revision')";
	}

	if ( ! empty( $where_parts ) ) {
		$where = 'WHERE ' . implode( ' AND ', $where_parts );
	} else {
		$where = '';
	}

	$query = "SELECT ID, post_content FROM $wpdb->posts $where";

	$num_updated_posts = 0;
	foreach( new WP_CLI\Iterators\Query( $query ) as $post ) {

		$num_sideloaded_images = 0;

		if ( empty( $post->post_content ) ) {
			continue;
		}

		$document = new DOMDocument;
		@$document->loadHTML( $post->post_content );

		$img_srcs = array();
		foreach( $document->getElementsByTagName( 'img' ) as $img ) {

			// Sometimes old content management systems put spaces in the URLs
			$img_src = esc_url_raw( str_replace( ' ', '%20', $img->getAttribute( 'src' ) ) );
			if ( ! empty( $assoc_args['domain'] ) && $assoc_args['domain'] != parse_url( $img_src, PHP_URL_HOST ) ) {
				continue;
			}

			// Don't permit the same media to be sideloaded twice for this post
			if ( in_array( $img_src, $img_srcs ) ) {
				continue;
			}

			// Most of this was stolen from media_sideload_image
			$tmp = download_url( $img_src );

			// Set variables for storage
			// fix file filename for query strings
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $img_src, $matches );
			$file_array = array();
			$file_array['name'] = sanitize_file_name( urldecode( basename( $matches[0] ) ) );
			$file_array['tmp_name'] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
				@unlink( $file_array['tmp_name'] );
				$file_array['tmp_name'] = '';
				WP_CLI::warning( $tmp->get_error_message() );
				continue;
			}

			// do the validation and storage stuff
			$id = media_handle_sideload( $file_array, $post->ID );
			// If error storing permanently, unlink
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
				WP_CLI::warning( $id->get_error_message() );
				continue;
			}

			$new_img = wp_get_attachment_image_src( $id, 'full' );
			$post->post_content = str_replace( $img->getAttribute( 'src' ), $new_img[0], $post->post_content );
			$num_sideloaded_images++;
			$img_srcs[] = $img_src;

			if ( $assoc_args['verbose'] ) {
				WP_CLI::line( sprintf( "Replaced '%s' with '%s' for post #%d", $img_src, $new_img[0], $post->ID ) );
			}

		}

		if ( $num_sideloaded_images ) {
			$num_updated_posts++;
			$wpdb->update( $wpdb->posts, array( 'post_content' => $post->post_content ), array( 'ID' => $post->ID ) );
			clean_post_cache( $post->ID );
			if ( $assoc_args['verbose'] ) {
				WP_CLI::line( sprintf( "Sideloaded %d media references for post #%d", $num_sideloaded_images, $post->ID ) );
			}
		} else if ( ! $num_sideloaded_images && $assoc_args['verbose'] ) {
			WP_CLI::line( sprintf( "No media sideloading necessary for post #%d", $post->ID ) );
		}
	}

	WP_CLI::success( sprintf( "Sideload complete. Updated media references for %d posts.", $num_updated_posts ) );
};

WP_CLI::add_command( 'media sideload', $run_sideload_media_command );
