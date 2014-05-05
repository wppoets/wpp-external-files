<?php
/**
 * Plugin Name: WPP Import External Files
 * Plugin URI: http://wppoets.com/plugins/external-files.html
 * Description: Allows content from external sources to be downloaded and attached to there respected Post/Page/Custom Content. This helps to prevent the user experience from getting ruined by dead images and external 404 errors.
 * Version: 0.9.0
 * Author: WP Poets <plugins@wppoets.com>
 * Author URI: http://wppoets.com
 * License: GPLv2 (dual-licensed)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
/**
 * Copyright (c) 2014, WP Poets and/or its affiliates <copyright@wppoets.com>
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
/**
 * @author Michael Stutz <michaeljstutz@gmail.com>
 */
defined( 'ABSPATH' ) or die(); // We should not be loading this outside of wordpress

defined( 'WPP_EXTERNAL_FILES_VERSION_NUM' )       or define( 'WPP_EXTERNAL_FILES_VERSION_NUM', '0.9.0' );
//defined( 'WPP_EXTERNAL_FILES_ASSETS_VERSION_NUM') or define( 'WPP_EXTERNAL_FILES_ASSETS_VERSION_NUM', WPP_SLIDESHOW_VERSION_NUM ); 
defined( 'WPP_EXTERNAL_FILES_ASSETS_VERSION_NUM') or define( 'WPP_EXTERNAL_FILES_ASSETS_VERSION_NUM', date('YmdHis') ); // Devolopment Only
defined( 'WPP_EXTERNAL_FILES_TEXT_DOMAIN' )       or define( 'WPP_EXTERNAL_FILES_TEXT_DOMAIN', 'wpp-external-files' );
defined( 'WPP_EXTERNAL_FILES_PLUGIN_FILE' )       or define( 'WPP_EXTERNAL_FILES_PLUGIN_FILE', __FILE__ );
defined( 'WPP_EXTERNAL_FILES_PLUGIN_PATH' )       or define( 'WPP_EXTERNAL_FILES_PLUGIN_PATH', dirname(__FILE__ ) );
defined( 'WPP_EXTERNAL_FILES_NAMESPACE_PATH' )    or define( 'WPP_EXTERNAL_FILES_NAMESPACE_PATH', WPP_EXTERNAL_FILES_PLUGIN_PATH . '/src/namespace' );
defined( 'WPP_EXTERNAL_FILES_FUNCTION_PATH' )     or define( 'WPP_EXTERNAL_FILES_FUNCTION_PATH', WPP_EXTERNAL_FILES_PLUGIN_PATH . '/src/functions' );
defined( 'WPP_EXTERNAL_FILES_FILTER_FILE' )       or define( 'WPP_EXTERNAL_FILES_FILTER_FILE', 'wpp-external-files/wpp-external-files.php' );


//Include the required function files
require_once( WPP_EXTERNAL_FILES_FUNCTION_PATH . DIRECTORY_SEPARATOR . 'wpp-common.php' );
require_once( WPP_EXTERNAL_FILES_FUNCTION_PATH . DIRECTORY_SEPARATOR . 'wpp-external-files-autoloader.php' );
require_once( WPP_EXTERNAL_FILES_FUNCTION_PATH . DIRECTORY_SEPARATOR . 'wpp-external-files-helper.php' );

//Make the magic happen!
\WPP\External_Files\Plugin::init();