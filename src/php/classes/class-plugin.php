<?php namespace WPP\External_Files;
/**
 * Copyright (c) 2014, WP Poets and/or its affiliates <wppoets@gmail.com>
 * Portions of this distribution are copyrighted by:
 *   Copyright (c) 2014 Michael Stutz <michaeljstutz@gmail.com>
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
class Plugin extends \WPP\External_Files\Base\Plugin {

	/**
	 * Initialization point for the configuration
	 * 
	 * @return void No return value
	 */
	static public function init_config() {
		static::set_config_instance( Config::init() ); //Required before pretty much anything!
		parent::init_config();
		static::set_config( 'id', 'wpp-external-files' );
		static::set_config( 'option_key', 'wpp_external_files_options' );
		static::set_config( 'option_autoload', FALSE );
		static::set_config( 'enable_admin_sections', TRUE );
		static::set_config( 'enable_admin_pages', TRUE );
		static::set_config( 'enable_content_types', FALSE );
		static::set_config( 'enable_meta_boxes', TRUE );
		static::set_config( 'enable_shortcodes', FALSE );

		$config = static::get_config_instance();

		//Add the meta box and set configuration
		$url_meta_box = $config::add_meta_box( '\WPP\External_Files\Meta_Boxes\Url_Meta_Box' );
		$config::config_meta_box( $url_meta_box, 'metadata_key_external_url', '_wpp_external_url' );
		
		//Add admin section
		$admin_section = $config::add_admin_section( '\WPP\External_Files\Admin_Sections\Admin_Section' );
		$config::config_meta_box( $admin_section, 'metadata_key_external_url', '_wpp_external_url' );
		
		//Add the setting admin page and set configuration
		$settings_admin_page = $config::add_admin_page( '\WPP\External_Files\Admin_Pages\Settings_Admin_Page' );
		$config::config_meta_box( $settings_admin_page, 'option_key', static::get_config( 'option_key' ) );

	}

	/**
	 * Method for after init has completed
	 * 
	 * @return void No return value
	 */
	static public function init_done() {
		parent::init_done();
		//$data_dump = Config::_get_raw_dump();
		//static::debug(__METHOD__, $data_dump[0] );
	}

}
