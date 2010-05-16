<?php
/*
Plugin Name: Hetjens MediaRSS
Plugin URI: http://hetjens.com/wordpress/hetjens_mediarss/
Version: 0.1
Description: Adds post thumbnails to feeds via MediaRSS specification.
Author: Philip Hetjens
Author URI: http://hetjens.com
Text Domain: Hetjens_MediaRSS
License: GPL
*/

/*
  Copyright 2010 Philip Hetjens (email : Philip at Hetjens dot eu);

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Hetjens_MediaRSS {

  /*
   * Registers this plugin
   */
  function register() {
    add_theme_support('post-thumbnails', array('post'));

    add_action('rss2_ns',array(&$this,'feed_ns'));
    add_action('atom_ns',array(&$this,'feed_ns'));

    add_action('rss2_item',array(&$this,'feed_rss_media'));
    add_action('atom_entry',array(&$this,'feed_atom_media'));
  }

  /**
   * Code added to RSS-Namespace
   */
  function feed_ns() {
    echo 'xmlns:media="http://search.yahoo.com/mrss/" ';
  }

  /**
   * Code added to an RSS-Item
   */
  function feed_rss_media() {
    $data = $this->get_thumbnail();
    if ($data != false) {
      echo '<enclosure url="'.$data['url'].'" type="'.$data['mime'].'" />'."\n";
      echo '<media:content url="'.$data['url'].'" type="'.$data['mime'].'" expression="sample" />'."\n";
      echo '<media:thumbnail url="'.$data['url'].'" type="'.$data['mime'].'" width="'.get_option('thumbnail_size_w').'" height="'.get_option('thumbnail_size_h').'" />'."\n";
    }
  }

  /**
   * Code added to an Atom-Entry
   */
  function feed_atom_media() {
    $data = $this->get_thumbnail();
    if ($data != false) {
      echo '<media:content url="'.$data['url'].'" type="'.$data['mime'].'" expression="sample" />'."\n";
      echo '<media:thumbnail url="'.$data['url'].'" type="'.$data['mime'].'" width="'.get_option('thumbnail_size_w').'" height="'.get_option('thumbnail_size_h').'" />'."\n";
    }
  }

  /**
   * Gets the URL of the post thumbnail of the current post
   * @return string URL to Thumbnail-File
   */
  function get_thumbnail() {
    global $post;

    $id = get_post_thumbnail_id($post->ID);
    $url = wp_get_attachment_image_src($id,'thumbnail',false);
    if ($url[0] != '') {
      $p = get_post($id);
      return array(
        'url' => $url[0],
        'mime' => $p->post_mime_type
      );
    }
    else
      return false;
  }
}

/* Initialise outselves */
add_action('plugins_loaded', create_function('','$Hetjens_MediaRSS = new Hetjens_MediaRSS(); $Hetjens_MediaRSS->register();'));
?>