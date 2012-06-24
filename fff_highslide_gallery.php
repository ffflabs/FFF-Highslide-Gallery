<?php
/*
Plugin Name: FFF Highslide Gallery
Plugin URI: http://ffflabs.com
Description: Changes to the lightbox view in galleries to use highslide slideshow. It includes a shortcode [IFRAME] to display highslide iframes too. You are strongly encouraged to download <a href="http://highslide.com/">highslide</a> yourself and give them proper credit 
Author: Felipe Figueroa
Version: 0.7
Author URI: http://ffflabs.com
*/


if(!defined('URL')) define('URL',get_bloginfo('siteurl'));

add_action( 'init', 'fff_highslide_textdomain' );
add_action( 'wp_head', 'fff_highslide_wp_head' );
add_action('init', 'init_highslide'); 

add_shortcode( 'gallery', 'fff_highslide' );
add_shortcode( 'iframe', 'fff_iframe' );


function fff_iframe($atts) {
    // should be used as [iframe url="_TARGET_URL_OF_YOUR_IFRAME_" src="_A_LOCAL_IMAGE_TO_DISPLAY_" ]
	extract( shortcode_atts( array(
            'url' => 'http://www.lupa.io/tag/jaivas+machu+pichu',
            'img' => 'http://www.saborizante.com/up/2011/07/jaivas.jpg',
    ), $atts ) );

        echo '  <a onclick="return hs.htmlExpand(this,iframegroup)" href="'.$url.'" class=" "><img src="'.$img.'" alt="" title="image" width="100%" class="aligncenter size-full" /></a>';
    
}


function fff_highslide_textdomain() {
	if ( function_exists('load_plugin_textdomain') ) {
		if ( !defined('WP_PLUGIN_DIR') ) {
			load_plugin_textdomain('fff_highslide', str_replace( ABSPATH, '', dirname(__FILE__) ) );
		} else {
			load_plugin_textdomain('fff_highslide', false, dirname( plugin_basename(__FILE__) ) );
		}
	}
}

function fff_highslide_wp_head() {
	global $wp_query;

	if ( !defined('WP_PLUGIN_DIR') )
		$plugin_dir = str_replace( ABSPATH, '', dirname(__FILE__) );
	else
		$plugin_dir = dirname( plugin_basename(__FILE__) );


	if ( !is_admin()) {
				
				

		echo '<link rel="stylesheet" type="text/css" href="'.URL .'/'. PLUGINDIR . '/' . $plugin_dir . '/highslide.css" />'."\n";
	echo "<script type='text/javascript'>hs.graphicsDir = \"".URL .'/'. PLUGINDIR . '/' . $plugin_dir . "/graphics/\";</script>";
	
		
	}
}


function init_highslide() {
	if ( !defined('WP_PLUGIN_DIR') )
		$plugin_dir = str_replace( ABSPATH, '', dirname(__FILE__) );
	else
		$plugin_dir = dirname( plugin_basename(__FILE__) );

if (!is_admin()) {
    wp_deregister_script( 'jquery' );
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
		wp_enqueue_script('jquery');
			wp_enqueue_script( 'fff_highslide', URL .'/' . PLUGINDIR . '/' . $plugin_dir . '/highslide-full.packed.js', array('jquery'));
			wp_enqueue_script( 'fff_highslide_config', URL .'/' . PLUGINDIR . '/' . $plugin_dir . '/highslide.config.js', array('fff_highslide') );
		}
    }  




function fff_highslide($attr) {
	 
	global $post, $wp_query;

	// Allow fff_highslide_gallery to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	
	if ( !isset( $attr['orderby'] ) && get_bloginfo('version')<2.6 ) {		$attr['orderby'] = 'menu_order ASC, ID ASC';	}
	 		
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => $columns,
		'size'       => $size,
		'include'    => '',
		'exclude'    => '',
		'lightboxsize' => $lightboxsize,
		'meta'       => 'false',
		'class'      => 'gallery1',
		'nofollow'   => false,
		'from'       => '',
		'size'		 => $size,
		'num'        => $maximg,
		'page'       => $wp_query->query_vars['page'],
		'before' => '<div class="gallery_pagenavi">' . __('Pages:'), 'after' => '</div>',
		'link_before' => '', 'link_after' => '',
		'next_or_number' => 'number', 'nextpagelink' => __('Next page'),
		'previouspagelink' => __('Previous page'), 'pagelink' => '%', 'pagenavi' => 1
	), $attr));
	
	
	
	if(!$size) $size = 'thumbnail';
	// maximum number of columns and total images to display. The rest will still come out as you browse through the highslide slideshow.
	$columns = intval($columns)? intval($columns) : 4;
	$maximg=intval($maximg)? intval($maximg) : 4;
	$id = intval($id);

	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';
		
	$total = count($attachments)-$from;
	
	if ( !$page ) $page = 1;
		
	if ( is_numeric($from) && !$num ) :
		$attachments = array_splice($attachments, $from);
	elseif ( is_numeric($page) && is_numeric($num) && $num>0 ) :
		if ( $total%$num == 0 ) $numpages = (int)($total/$num);
		else $numpages = (int)($total/$num)+1;
		$attachments = array_splice($attachments, ($page-1)*$num+$from, $num);
	endif;
	


	$listtag = tag_escape($listtag);
	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	

	
	$output ="<div id='gallery-".$post->ID."' class='gallery gallery-columns-4 gallery-size-thumbnail'>";
$fotonum=0;
	foreach ( $attachments as $id => $attachment ) {
		if ( $attachment->post_type == 'attachment' ) {
			$thumbnail_link = wp_get_attachment_image_src($attachment->ID, $size, false);
			$lightbox_link = wp_get_attachment_image_src($attachment->ID, $lightboxsize, false);
			trim($attachment->post_content);
			trim($attachment->post_excerpt);
		$fotonum++;

$output .="<dl class='gallery-item' ";
if ($fotonum>$maximg) $output .= 'style="display:none;"';
$output .= "><dt class='gallery-icon'>";
	$output .= '<a href="'.$lightbox_link[0].'" class="highslide" 	onclick="return hs.expand(this, {	slideshowGroup: \'group'.$post->ID.'\'} )">';
			$output .= '<img src="'.$thumbnail_link[0].'" width="'.$thumbnail_link[1].'" height="'.$thumbnail_link[2].'" alt="'.addslashes($attachment->post_title).'" />';
			$output .= '</a></dt></dl>'."\n";			
			
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<div style="clear: both;"></div>';
		}
	}
	
	$output .= '<div style="clear: both;"></div></div>	';

	return $output;
}
