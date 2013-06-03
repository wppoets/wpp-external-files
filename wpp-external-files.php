<?php
/**
 * Plugin Name: WPP Import External Files
 * Plugin URI: http://wppoets.com/plugins/external-files.html
 * Description: Allows content from external sources to be downloaded and attached to there respected Post/Page/Custom Content. This helps to prevent the user experience from getting ruined by dead images and external 404 errors.
 * Version: 0.1
 * Author: WP Poets <plugins@wppoets.com>
 * Author URI: http://wppoets.com
 * License: GPLv2 (dual-licensed)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
/**  
 * Copyright (c) 2013, WP Poets and/or its affiliates <plugins@wppoets.com>
 * Portions of this distribution are copyrighted by:
 *   Copyright (c) 2013 Michael Stutz <michaeljstutz@gmail.com>
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
if (!defined('ABSPATH')) die(); // We should not be loading this outside of wordpress
if (!defined('WPP_EXTERNAL_FILES_VERSION_NUM')) define('WPP_CONTENT_ALIAS_VERSION_NUM', '0.1');
if (!defined('WPP_EXTERNAL_FILES_PLUGIN_FILE')) define('WPP_CONTENT_ALIAS_PLUGIN_FILE', __FILE__);
if (!defined('WPP_EXTERNAL_FILES_PLUGIN_PATH')) define('WPP_CONTENT_ALIAS_PLUGIN_PATH', dirname(__FILE__));
if (!defined('WPP_EXTERNAL_FILES_FILTER_FILE')) define('WPP_CONTENT_ALIAS_FILTER_FILE', 'wpp-content-alias/wpp-content-alias.php');

if(!class_exists('WPP_ExternalFiles')) require_once(WPP_CONTENT_ALIAS_PLUGIN_PATH . '/core/WPP_ExternalFiles.php');
WPP_ExternalFiles::init();