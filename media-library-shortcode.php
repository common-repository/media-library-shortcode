<?php
/**
 * Plugin Name: Media Library Shortcode
 * Plugin URI: http://headwaymarketing.com/media-library-shortcode-wordpress-plugin
 * Description: The Media Library Shortcode plugin allows you to insert media library items such as documents in list form using a simple shortcode. See the "Media > Shortcode" menu page for further plugin details.
 * Version: 1.0
 * Author: Headway Marketing
 * Author URI: http://headwaymarketing.com/
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

// Add Media Library Menu Item
add_action('admin_menu', 'media_library_menu_item');

	// Configure Menu Item
	function media_library_menu_item() {
		add_media_page('How to use the Media Library Shortcode', 'Shortcode', 1, 'shortcode', 'media_library_shortcode_page');
		}
	
	// Configure Menu Page
	function media_library_shortcode_page() { ?>

   	    <div class="wrap">
	        <h2>How to use the Media Library Shortcode</h2>
            <p>This plugin allows you to use a shortcode to create a list of documents using the document's corresponding ID. Example: <tt>[attachment id="1,2,3"]</tt>. The list of ID's must be enclosed in quotes and comma separated. You can also sort documents by type and find document ID's in the media library.</p>
            <h3>Features of this Plugin Include</h3>
            <ul>
            	<li>Add the shortcode to pages, posts and widgets.</li>
            	<li>Sort media library documents by type.</li>
            	<li>Find document ID's in the media library.</li>
            </ul>
		</div>
        
    <?php } 
	
// Start Media Library Filter by Mime Type Menu
function modify_post_mime_types($post_mime_types) {
	$post_mime_types['application/pdf'] = array(__('PDF', 'pdf'), __('Manage PDF', 'pdf'), __ngettext_noop('PDF (%s)', 'PDF (%s)', 'pdf'));
	$post_mime_types['application/msword'] = array(__('DOC', 'doc'), __('Manage DOC', 'doc'), __ngettext_noop('DOC (%s)', 'DOC (%s)', 'doc'));
	$post_mime_types['application/vnd.ms-excel'] = array(__('XLS', 'xls'), __('Manage XLS', 'xls'), __ngettext_noop('XLS (%s)', 'XLS (%s)', 'xls'));
	$post_mime_types['application/zip'] = array(__('Zip', 'zip'), __('Manage Zip', 'zip'), __ngettext_noop('Zip (%s)', 'Zip (%s)', 'zip'));
	return $post_mime_types;}
	add_filter('post_mime_types', 'modify_post_mime_types');	

// Start Media Library ID Column Addition
function mediaColumnsHeader($columns) {
	$columns['medID'] = __('ID');
	return $columns;}
	add_filter( 'manage_media_columns', 'mediaColumnsHeader' );

function mediaColumnsRow($columnName, $columnID){
	if($columnName == 'medID'){
		echo $columnID;}
	}
	add_filter( 'manage_media_custom_column', 'mediaColumnsRow', 10, 2 );
 
// Add the ability to insert shortcodes in sidebar widgets
add_filter('widget_text','do_shortcode');

// Create media library shortcode for attachment array [attachment id="1,2,3,4"]
function ml_shortcode($atts){
  extract(shortcode_atts(array( 
    'id' => '' ), 
    $atts));    
  if($id=='')return ''; // (error checking) return '' if blank
  $begin = '<ul class="attachment_list">';
  $parts = explode(',', $id);
  $list = '';
  foreach ($parts as $el){ // creates the list items
        $list = $list.'<li>'.wp_get_attachment_link($el).'</li>';
    }
  $end = '</ul>';
  return $begin.$list.$end; // returns the full list
}
add_shortcode('attachment', 'ml_shortcode');

?>