<?php namespace WPP\External_Files\Meta_Boxes;
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
class Url_Meta_Box extends \WPP\External_Files\Base\Meta_Box {

	/**
	 * Initialization point for the configuration
	 * 
	 * @return void No return value
	 */
	static public function init_config() {
		parent::init_config();
		static::set_config( 'id', 'wpp-external-files-url-meta-box' );
		static::set_config( 'display_title', 'Source Url' );
		static::set_config( 'display_content', 'side' );
		static::set_config( 'display_priority', 'core' );
		static::set_config( 'html_form_prefix', 'wpp_external_files_fields' );
		static::set_config( 'html_class_prefix', 'wpp-external-files-' );
		static::set_config( 'html_id_prefix', 'wpp-external-files-' );
		$post_types_includes = static::get_config( 'post_types_includes' );
		if ( ! is_array( $post_types_includes ) ) {
			$post_types_includes = array();
		}
		if ( ! in_array( 'attachment', $post_types_includes ) ) {
			$post_types_includes[] = 'attachment';
			static::set_config( 'post_types_includes', $post_types_includes );
		}
		static::debug(__METHOD__, 'test');
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
		$form_data = array();
		$metadata_key_external_url = static::get_config( 'metadata_key_external_url' );
		$html_id_prefix = static::get_config( 'html_id_prefix' );
		$html_form_prefix = static::get_config( 'html_id_prefix' );
		if ( ! empty( $metadata_key_external_url ) ) {
			$form_data = get_post_meta( $post->ID, $metadata_key_external_url, TRUE );
		}
		?>
		<?php if ( ! empty( $form_data ) ) : ?>
		<p>
			<div class="wpp-slideshow-field-block">
				<input type="text" id="<?php echo $html_id_prefix . 'url'; ?>" class="widefat urlfield" readonly="readonly" name="<?php echo $html_form_prefix; ?>[url]" value="<?php echo ( empty( $form_data ) ? '' : $form_data ); ?>" /><br />
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