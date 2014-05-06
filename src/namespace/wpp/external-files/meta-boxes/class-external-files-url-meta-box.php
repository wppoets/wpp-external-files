<?php namespace WPP\External_Files\Meta_Boxes;
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
class External_Files_Url_Meta_Box extends \WPP\External_Files\Base\Meta_Box {
 	
	/** Used to set the meta-box ID */
	const ID = 'wpp-external-files-url-meta-box';

	/** Used to store the meta-box title */
	const TITLE = 'Source Url';

	/** Used to store the plugin file location */
	const PLUGIN_FILE = WPP_EXTERNAL_FILES_PLUGIN_FILE;

	/** Used to store the asset version */
	const ASSET_VER = WPP_EXTERNAL_FILES_ASSETS_VERSION_NUM;

	/** Used to store the text domain */
	const TEXT_DOMAIN = WPP_EXTERNAL_FILES_TEXT_DOMAIN;

	/** Used to store the nonce action */
	const NONCE_ACTION = __FILE__;

	/** Used to store waht context the meta-box should be located */
	const CONTEXT = 'side'; //('normal', 'advanced', or 'side')

	/** Used to store what priority the meta-box should have */
	const PRIORITY = 'core'; //('high', 'core', 'default' or 'low')

	/** Used to store the form prefex */
	const HTML_FORM_PREFIX = 'wpp_external_files_fields'; // should only use [a-z0-9_]

	/** Used to store the form prefex */
	const HTML_CLASS_PREFIX = 'wpp-external-files-'; // should only use [a-z0-9_-]

	/** Used to store the form prefex */
	const HTML_ID_PREFIX = 'wpp-external-files-'; // should only use [a-z0-9_-]

	/** Used as the metadata key prefix */
	const METADATA_KEY_PREFIX = '_wpp_external_files_';

	/**
	 * Initialization point for the static class
	 *
	 * @return void No return value
	 */
	static public function init( $options = array() ) {
		parent::init( wpp_array_merge_nested(
			array(
				'metadata_key_external_url' => '',
			),
			$options
		) );
	}

	/**
	 * WordPress action for displaying the meta-box
	 *
	 * @param object $post The post object the metabox is working with
	 * @param array $callback_args Extra call back args
	 *
	 * @return void No return value
	 */
	static public function action_meta_box_display( $post, $callback_args ) {
		$options = static::get_options();
		$form_data = array();
		if ( ! empty( $options[ 'metadata_key_external_url' ] ) ) {
			$form_data = get_post_meta( $post->ID, $options[ 'metadata_key_external_url' ], TRUE );
		}
		?>
		<?php if ( ! empty( $form_data ) ) : ?>
		<p>
			<div class="wpp-slideshow-field-block">
				<input type="text" id="<?php echo static::HTML_ID_PREFIX . 'url'; ?>" class="widefat urlfield" readonly="readonly" name="<?php echo static::HTML_FORM_PREFIX; ?>[url]" value="<?php echo ( empty( $form_data ) ? '' : $form_data ); ?>" /><br />
			</div>
		</p>
		<?php else : ?>
		<style>
			#wpp-external-files-url-meta-box {
				display: none;
			}
		</style>
		<?php endif; ?>
		<?php
	}

}
