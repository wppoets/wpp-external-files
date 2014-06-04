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
class Config extends \WPP\External_Files\Base\Instance_Config {

	/**
	 * Initialization point for the configuration
	 * 
	 * Because this is used before the overall config you must 
	 * use the $config class directly
	 *
	 * @return void No return value
	 */
	static public function init_config() {
		parent::init_config();
		$config = static::get_config_instance();
		$config::set( 'text_domain', WPP_EXTERNAL_FILES_TEXT_DOMAIN );
		$config::set( 'asset_version', WPP_EXTERNAL_FILES_ASSETS_VERSION );
		$config::set( 'base_url', WPP_EXTERNAL_FILES_BASE_URL );
		$config::set( 'base_scripts_url', WPP_EXTERNAL_FILES_BASE_URL_SCRIPTS );
		$config::set( 'base_styles_url', WPP_EXTERNAL_FILES_BASE_URL_STYLES );
		$config::set( 'extension_js', WPP_EXTERNAL_FILES_EXTENTION_SCRIPTS );
		$config::set( 'extension_css', WPP_EXTERNAL_FILES_EXTENTION_STYLES );
		$config::set( 'meta_key_prefix', '' );
		$config::set( 'cache_group', WPP_EXTERNAL_FILES_CACHE_GROUP );
		$config::set( 'id', 'wpp-external-files-config', $config ); // All instances require an id :)
	}

}
