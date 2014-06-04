<?php namespace WPP\External_Files\Admin_Sections;
/**
 * Copyright (c) 2014, WP Poets and/or its affiliates <wppoets@gmail.com>
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
defined( 'WPP_EXTERNAL_FILES_VERSION_NUM' ) or die(); //If the base plugin is not used we should not be here
/**
 * @author Michael Stutz <michaeljstutz@gmail.com>
 */
class Admin_Section extends \WPP\External_Files\Base\Admin_Section {

	/** */
	const PREG_EXTRACT_URL = '/(src|href)\s*=\s*[\"\']([^\"\']+)[\"\']/';

	/** */
	const STRING_REGEX_TOKEN = 'REGEX::';

	/** */
	const PHP_SET_TIME_LIMIT = 60;

	/**
	 * Initialization point for the configuration
	 * 
	 * @return void No return value
	 */
	static public function init_config() {
		parent::init_config();
		static::set_config( 'id', 'wpp-external-files-admin-section' );
		static::set_config( 'enable_save_post', TRUE );
		static::set_config( 'enable_save_post_nonce_check', TRUE );
		static::set_config( 'enable_save_post_revision_check', TRUE );
		static::set_config( 'enable_save_post_autosave_check', TRUE );
		static::set_config( 'enable_save_post_check_capabilities_check', TRUE );
		static::set_config( 'enable_save_post_single_run', FALSE );
		static::set_config( 'save_post_check_capabilities', array() );
		static::debug(__METHOD__, 'TESTING');
	}

	/**
	 * WordPress action for saving the post
	 * 
	 * @return void No return value
	 */
	static public function action_save_post( $post_id ) {
		if ( ! parent::action_save_post( $post_id ) ) {
			return;
		}
		$options = static::get_option();
		if ( empty( $options['enabled'] ) ) {
			return;
		}
		if ( empty( $options['tag_regex'] ) ) {
			return;
		}
		add_filter('sanitize_file_name', array( static::current_instance(), 'filter_sanitize_file_name_16330' ), 100000);
		remove_action( 'save_post', array( static::current_instance(), 'action_save_post' ) );
    	$post = get_post( $post_id );
		$check_content = array(
			'items' => array(
				0 => &$post->post_content,
				1 => &$post->post_excerpt,
			),
			'checksums' => array(
				0 => hash( 'crc32', $post->post_content ),
				1 => hash( 'crc32', $post->post_excerpt ),
			),
		);
		foreach ( $check_content['items'] as &$content ) {
			$matched_elements = array();
			if ( preg_match_all( $options[ 'tag_regex' ], $content, $matched_elements ) ) {
				foreach ( $matched_elements[0] as $element ) {
					$matched_url = array();
					if ( preg_match( static::PREG_EXTRACT_URL, $element, $matched_url ) ) {
						$url = empty( $matched_url[2] ) ? NULL : $matched_url[2];
						if ( empty( $url ) ) {
							continue;
						}
						if ( empty( $options[ 'import_extensions' ] ) ) {
							continue;
						}
						$url_parts = parse_url( $url );
						if ( empty( $url_parts['path'] ) ) {
							continue;
						}
						$path_parts = pathinfo( $url_parts['path'] );
						if ( empty( $path_parts['extension'] ) ) {
							continue;
						}
						// Check to see if it is a valid file extentions
						if ( ! static::custom_find_match( explode( PHP_EOL, $options[ 'import_extensions' ] ), $path_parts['extension'] ) ) {
							continue; // If we didnt find a matching extention skip url
						}
						// Make sure it is not an excluded url
						$excluded_urls = explode( PHP_EOL, $options[ 'excluded_urls' ] );
						$home_url_parts = parse_url( home_url() );
						$excluded_urls[] = '//' . $home_url_parts[ 'host' ]; // Add the current hostname of the home_url()
						if ( static::custom_find_match( $excluded_urls, $url ) ) {
							continue; // If we didnt find a matching extention skip url
						}
						$attachment_id = static::find_attachment_id_by_external_url( $url );
						if ( empty( $attachment_id ) ) { // We cant find it so we need to add it
							defined('STDIN') or set_time_limit( static::PHP_SET_TIME_LIMIT ); //If we are not running from the command line change the time limit
							$tmp_name = apply_filters( $options[ 'wp_filter_pre_tag' ] . 'tmp_name_download_url', NULL, $url );
							if ( $tmp_name === FALSE ) { //
								continue;
							} else if ( empty( $tmp_name ) ) {
								$tmp_name = download_url( $url );
							} else if ( ! is_readable( $tmp_name ) ) {
								continue;
							}
							$file_array = array(
								'name' => basename( $url_parts['path'] ),
								'tmp_name' => $tmp_name,
							);
							// If error storing temporarily, unlink
							if ( is_wp_error( $tmp_name ) ) {
								@unlink( $file_array[ 'tmp_name' ] );
								$file_array[ 'tmp_name' ] = '';
							}
							$attachment_id = media_handle_sideload( $file_array, $post_id, '' );
							// If error storing permanently, unlink
							if ( is_wp_error( $attachment_id ) ) {
								@unlink( $file_array['tmp_name'] );
								$attachment_id = NULL;
							} else {
								add_post_meta( $attachment_id, $options[ 'metadata_key_external_url' ], $url, TRUE ) or update_post_meta( $attachment_id, $options[ 'metadata_key_external_url' ], $url );
							}
						}
						if ( empty ( $attachment_id ) ) {
							continue; // Something went wrong, just skip
						}
						$attachment_url = wp_get_attachment_url( $attachment_id );
						if ( empty ( $attachment_url ) ) {
							continue; // Something went wrong, just skip
						}
						$new_element = str_replace( $url, $attachment_url, $element );
						$content = str_replace( $element, $new_element, $content );
					}
				}
			}
		}
		$changed = FALSE;
		foreach ( $check_content['items'] as $item_key => &$content ) {
			if ( hash( 'crc32', $content ) !== $check_content[ 'checksums' ][ $item_key ] ) {
				$changed = TRUE;
			}
		}
		if ( $changed ) {
			wp_update_post( $post );
		}
		add_action( 'save_post', array( static::current_instance(), 'action_save_post' ) );
		remove_filter('sanitize_file_name', array( static::current_instance(), 'filter_sanitize_file_name_16330' ) );
	}

	
	/**
	 * WordPress filter for sanitizing the file name
	 * 
	 * Added becuase of WP bug http://core.trac.wordpress.org/ticket/16330
	 *
	 * @return void No return value
	 */
	public static function filter_sanitize_file_name_16330( $file_name ) {
		$decoded_file_name = urldecode( $file_name );
		$new_file_name = preg_replace( '/[^a-zA-Z0-9_.\-]/','-', $decoded_file_name );
		return $new_file_name;
	}

}
