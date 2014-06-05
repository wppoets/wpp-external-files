<?php namespace WPP\External_Files\Admin_Pages;
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
class Settings_Admin_Page extends \WPP\External_Files\Base\Admin_Page {
	
	/** */
	static private $_wp_options = array();
	
	/**
	 * Initialization point for the configuration
	 * 
	 * @return void No return value
	 */
	static public function init_config() {
		parent::init_config();
		static::set_config( 'id', 'wpp-external-files-settings-admin-page' );
		static::set_config( 'enable_admin_menu', TRUE );
		static::set_config( 'enable_admin_init', TRUE );
	}

	/**
	 * WordPress action for admin_init
	 * 
	 * @return void No return value
	 */
	static public function action_admin_init( ) {
		$current_instance = static::current_instance();
		$option_key = static::get_config( 'option_key' );

		register_setting(
			$option_key, // Option group
			$option_key, // Option name
			array( $current_instance, 'callback_sanitize_options' ) // Sanitize
		);
		add_settings_section(
			'settings_section', // ID
			'Import Settings', // Title
			array( $current_instance, 'callback_print_settings_section' ), // Callback
			'wpp-external-files-options' // Page
		);
		add_settings_field(
			'enable', // ID
			'Enabled', // Title 
			array( $current_instance, 'callback_print_enabled' ), // Callback
			'wpp-external-files-options', // Page
			'settings_section' // Section           
		);		
		add_settings_field(
			'import_tags', // ID
			'Included Tags', // Title 
			array( $current_instance, 'callback_print_import_tags' ), // Callback
			'wpp-external-files-options', // Page
			'settings_section' // Section           
		);
		add_settings_field(
			'import_extensions', // ID
			'File Extensions', // Title 
			array( $current_instance, 'callback_print_import_extensions' ), // Callback
			'wpp-external-files-options', // Page
			'settings_section' // Section           
		);
		add_settings_field(
			'excluded_urls', // ID
			'Excluded URLs*', // Title 
			array( $current_instance, 'callback_print_excluded_urls' ), // Callback
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
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin', 
			'Import External Files', 
			'manage_options', 
			static::get_config( 'option_key' ), 
			array( static::current_instance(), 'callback_create_admin_page' )
		);
	}

	/**
	 *
	 */
	static public function callback_create_admin_page() {
		self::$_wp_options[ static::current_instance() ] = static::get_option( static::get_config( 'option_key' ) );
		?>
		<div class="wrap">
			<h2>WPP Import External Files</h2>           
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( static::get_config( 'option_key' ) );
				do_settings_sections( 'wpp-external-files-options' );
				submit_button(); 
			?>
			</form>
		</div>
		<?php
	}

	/**
	 *
	 */
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

	/**
	 *
	 */
	static public function callback_print_settings_section() {
		//echo( 'Enter the urls you would like to exclude from import' );
	}

	/**
	 *
	 */
	static public function callback_print_enabled() {
		printf(
			'<input type="checkbox" value="checked" name="' . static::get_config( 'option_key' ) .'[enabled]" %s /> Should the plugin import on save_post? <br />',
			isset( self::$_wp_options[ static::current_instance() ]['enabled'] ) ? 'checked' : ''
		);
	}

	/**
	 *
	 */
	static public function callback_print_import_tags() {
		printf(
			'<input type="checkbox" value="checked" name="' . static::get_config( 'option_key' ) .'[import_tags][img]" %s /> Images <br />',
			isset( self::$_wp_options[ static::current_instance() ]['import_tags']['img'] ) ? 'checked' : ''
		);		
		printf(
			'<input type="checkbox" value="checked" name="' . static::get_config( 'option_key' ) .'[import_tags][a]" %s /> Links <br />',
			isset( self::$_wp_options[ static::current_instance() ]['import_tags']['a'] ) ? 'checked' : ''
		);
	}

	/**
	 *
	 */
	static public function callback_print_import_extensions() {
		echo('<em>Enter each extension on a single line. Regex is supported but must start with "REGEX::" ie "REGEX::/(jpg|jpeg|gif|png|pdf)/i"</em><br />');
		printf(
			'<textarea rows="6" id="import_extensions" class="widefat" name="' . static::get_config( 'option_key' ) .'[import_extensions]" >%s</textarea>',
			isset( self::$_wp_options[ static::current_instance() ]['import_extensions'] ) ? esc_attr( self::$_wp_options[ static::current_instance() ]['import_extensions'] ) : ''
		);
	}

	/**
	 *
	 */
	static public function callback_print_excluded_urls() {
		echo('<em>Enter each url on a single line. Regex is supported but must start with "REGEX::" ie "REGEX::/www\.mydomain\.com/i"</em><br />');
		printf(
			'<textarea rows="6" id="excluded_urls" class="widefat" name="' . static::get_config( 'option_key' ) .'[excluded_urls]" >%s</textarea>',
			isset( self::$_wp_options[ static::current_instance() ]['excluded_urls'] ) ? esc_attr( self::$_wp_options[ static::current_instance() ]['excluded_urls'] ) : ''
		);
		$home_url_parts = parse_url( home_url() );
		$auto_excluded_url = '//' . $home_url_parts[ 'host' ]; // Add the current hostname of the home_url()
		echo('<em>*Please note that "' . $auto_excluded_url . '" will be added automaticly</em><br />');
	}

}
