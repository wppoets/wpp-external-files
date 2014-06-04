<?php namespace WPP\External_Files;
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
class Admin extends \WPP\External_Files\Base\Admin {

	/** Used to enable the action admin_menu */
	const ENABLE_ADMIN_MENU = TRUE;

	/** Used to enable the action admin_init */
	const ENABLE_ADMIN_INIT = TRUE;

	/** Used to enable the action save_post */
	const ENABLE_SAVE_POST = TRUE;

	/** Used to set if the class uses action_save_post */
	const ENABLE_SAVE_POST_AUTOSAVE_CHECK = TRUE;

	/** Used to set if the class uses action_save_post */
	const ENABLE_SAVE_POST_REVISION_CHECK = TRUE;

	/** Used to set if the class uses action_save_post */
	const ENABLE_SAVE_POST_CHECK_CAPABILITIES_CHECK = TRUE;

	/** Used to enable the admin footer */
	const ENABLE_SAVE_POST_SINGLE_RUN = FALSE;

	/** Used to set if the class uses action_save_post */
	const SAVE_POST_CHECK_CAPABILITIES = '';

	/** */
	const PREG_EXTRACT_URL = '/(src|href)\s*=\s*[\"\']([^\"\']+)[\"\']/';

	/** */
	const STRING_REGEX_TOKEN = 'REGEX::';

	/** */
	const PHP_SET_TIME_LIMIT = 60;

	static private $_wp_options = array();

	/**
	 * Initialization point for the static class
	 * 
	 * @return void No return value
	 */
	static public function init( $options = array() ) {
		parent::init( $options );
	}

	/**
	 * WordPress action for admin_init
	 * 
	 * @return void No return value
	 */
	static public function action_admin_init( ) {
		$static_instance = get_called_class();
		$options = static::get_options();

		register_setting(
			$options[ 'wp_option_id' ], // Option group
			$options[ 'wp_option_id' ], // Option name
			array( $static_instance, 'callback_sanitize_options' ) // Sanitize
		);
		add_settings_section(
			'settings_section', // ID
			'Import Settings', // Title
			array( $static_instance, 'callback_print_settings_section' ), // Callback
			'wpp-external-files-options' // Page
		);
		add_settings_field(
			'enable', // ID
			'Enabled', // Title 
			array( $static_instance, 'callback_print_enabled' ), // Callback
			'wpp-external-files-options', // Page
			'settings_section' // Section           
		);		
		add_settings_field(
			'import_tags', // ID
			'Included Tags', // Title 
			array( $static_instance, 'callback_print_import_tags' ), // Callback
			'wpp-external-files-options', // Page
			'settings_section' // Section           
		);
		add_settings_field(
			'import_extensions', // ID
			'File Extensions', // Title 
			array( $static_instance, 'callback_print_import_extensions' ), // Callback
			'wpp-external-files-options', // Page
			'settings_section' // Section           
		);
		add_settings_field(
			'excluded_urls', // ID
			'Excluded URLs*', // Title 
			array( $static_instance, 'callback_print_excluded_urls' ), // Callback
			'wpp-external-files-options', // Page
			'settings_section' // Section           
		);
	}

	/**
	 * WordPress action for admin_menu
	 * 
	 * @return void No return value
	 */
	static public function action_admin_menu( ) {
		$static_instance = get_called_class();
		$options = static::get_options();
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin', 
			'Import External Files', 
			'manage_options', 
			$options[ 'wp_option_id' ], 
			array( $static_instance, 'callback_create_admin_page' )
		);
	}

	static public function callback_create_admin_page() {
		$static_instance = get_called_class();
		$options = static::get_options();
		self::$_wp_options[ $static_instance ] = get_option( $options[ 'wp_option_id' ] );
		?>
		<div class="wrap">
			<h2>WPP Import External Files</h2>           
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( $options[ 'wp_option_id' ] );
				do_settings_sections( 'wpp-external-files-options' );
				submit_button(); 
			?>
			</form>
		</div>
		<?php
	}

	static public function callback_sanitize_options( $input ) {
		$output = $input;
		if ( ! empty( $output[ 'import_tags' ] ) ) {
			$tag_regex = '/<(';
			//'/<(a|img)[^>]*>/i'
			$after_first_tag = FALSE;
			foreach ( $output[ 'import_tags' ] as $import_tag => $import_tag_enabled ) {
				if ( $after_first_tag ) {
					$tag_regex .= '|';
				}
				$tag_regex .= $import_tag;
				$after_first_tag = TRUE;
			}
			$tag_regex .= ')[^>]*>/i';
			$output['tag_regex'] = $tag_regex;
		}
		return $output;
	}

	static public function callback_print_settings_section() {
		//echo( 'Enter the urls you would like to exclude from import' );
	}

	static public function callback_print_enabled() {
		$static_instance = get_called_class();
		$options = static::get_options();
		printf(
			'<input type="checkbox" value="checked" name="' . $options[ 'wp_option_id' ] .'[enabled]" %s /> Should the plugin import on save_post? <br />',
			isset( self::$_wp_options[ $static_instance ]['enabled'] ) ? 'checked' : ''
		);
	}

	static public function callback_print_import_tags() {
		$static_instance = get_called_class();
		$options = static::get_options();
		printf(
			'<input type="checkbox" value="checked" name="' . $options[ 'wp_option_id' ] .'[import_tags][img]" %s /> Images <br />',
			isset( self::$_wp_options[ $static_instance ]['import_tags']['img'] ) ? 'checked' : ''
		);		
		printf(
			'<input type="checkbox" value="checked" name="' . $options[ 'wp_option_id' ] .'[import_tags][a]" %s /> Links <br />',
			isset( self::$_wp_options[ $static_instance ]['import_tags']['a'] ) ? 'checked' : ''
		);
	}

	static public function callback_print_import_extensions() {
		$static_instance = get_called_class();
		$options = static::get_options();
		echo('<em>Enter each extension on a single line. Regex is supported but must start with "REGEX::" ie "REGEX::/(jpg|jpeg|gif|png|pdf)/i"</em><br />');
		printf(
			'<textarea rows="6" id="import_extensions" class="widefat" name="' . $options[ 'wp_option_id' ] .'[import_extensions]" >%s</textarea>',
			isset( self::$_wp_options[ $static_instance ]['import_extensions'] ) ? esc_attr( self::$_wp_options[ $static_instance ]['import_extensions'] ) : ''
		);
	}

	static public function callback_print_excluded_urls() {
		$static_instance = get_called_class();
		$options = static::get_options();
		echo('<em>Enter each url on a single line. Regex is supported but must start with "REGEX::" ie "REGEX::/www\.mydomain\.com/i"</em><br />');
		printf(
			'<textarea rows="6" id="excluded_urls" class="widefat" name="' . $options[ 'wp_option_id' ] .'[excluded_urls]" >%s</textarea>',
			isset( self::$_wp_options[ $static_instance ]['excluded_urls'] ) ? esc_attr( self::$_wp_options[ $static_instance ]['excluded_urls'] ) : ''
		);
		$home_url_parts = parse_url( home_url() );
		$auto_excluded_url = '//' . $home_url_parts[ 'host' ]; // Add the current hostname of the home_url()
		echo('<em>*Please note that "' . $auto_excluded_url . '" will be added automaticly</em><br />');
	}

	static private function custom_find_match( $needles, $haystack ) {
		$found = FALSE;
		foreach ( (array) $needles as $needle ) {
			$needle = trim( $needle ); //Just in case
			if ( empty( $needle ) ) {
				continue;
			}
			if ( substr( $needle, 0, strlen( static::STRING_REGEX_TOKEN ) ) === static::STRING_REGEX_TOKEN ) {
				$needle = str_replace( static::STRING_REGEX_TOKEN, '', $needle ); // Remove the token
				if ( @preg_match( $needle, $haystack ) ) {
					$found = TRUE;
					break;
				}
			} else {
				if ( strpos( $haystack, $needle ) !== FALSE ) {
					$found = TRUE;
					break;
				}
			}
		}
		return $found;
	}

	static private function find_attachment_id_by_external_url( $external_url ) {
		$options = static::get_options();
		$find_posts = array(
			'post_type'        => 'attachment',
			'post_status'      => 'any',
			'numberposts'      => '1',
			'suppress_filters' => true,
			'fields'           => 'ids',
			'meta_query'       => array(
				array(
					'key'      => $options[ 'metadata_key_external_url' ],
					'value'    => $external_url,
					'compare'  => '=',
				),
			),
		);
		$wp_query = new \WP_Query( $find_posts );
		if ( ! empty( $wp_query->posts[0] ) ) {
			return $wp_query->posts[0];
		}
		return NULL;
	}
}
