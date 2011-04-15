<?php

function hmp_byline_meta_box( $post ) {
	?>
	<label for="hmp_byline">Byline
		<input type="text" name="hmp_byline" value="<?php hmp_get_byline( $post ) ?>" class="widefat" />
	</label>
	<?php
}

function hmp_brief_meta_box( $post ) {
	?>
	<textarea name="brief" class="widefat"><?php echo hmp_get_brief() ?></textarea>
	<?php
}
function hmp_brief_meta_box_submitted( $post ) {
	if ( isset( $_POST['brief'] ) )
		update_post_meta( $post->ID, '_brief', esc_html($_POST['brief']) );
}

function hmp_thumbnail_id_meta_box( $post ) {
	$ids = array_filter( array( hmp_get_main_image_id( $post ) ) );

	global $temp_ID;
    $post_image_id = $post->ID ? $post->ID : $temp_ID;

	global $temp_ID;
    $post_image_id = $post->ID ? $post->ID : $temp_ID;

	tj_register_custom_media_button( 'thumbnail_id', 'Use as Main Image', true, false, 150, 150 );
	$non_added_text = "No Main Image Added " .  ( ($hmp_url = hmp_get_url( $post ) ) ? '| <a href="' . esc_url( $hmp_url ) . '" target="_blank">Screenshot your site now</a>' : '' );

	tj_add_image_html_custom( 'thumbnail_id', ($ids ? 'Change' : 'Add') . ' Main Image', $post_image_id, $ids, false, 'width=150&height=150&crop=1', $non_added_text );

}

function hmp_thumbnail_id_meta_box_submitted( $post ) {
	if ( isset( $_POST['thumbnail_id'] ) )
		update_post_meta( $post->ID, '_thumbnail_id', (int) $_POST['thumbnail_id'] );
}


function hmp_gallery_meta_box( $post ) {
	$image_ids = hmp_get_gallery_ids( $post );

	global $temp_ID;
    $post_image_id = $post->ID ? $post->ID : $temp_ID;

	tj_register_custom_media_button( 'hmp_gallery_images', 'Add to Gallery', true, true, 150, 150 );
	$non_added_text = "No Gallery Images Added " .  ( ( $hmp_url = hmp_get_url( $post ) ) ? '| <a href="' . esc_url( $hmp_url ) . '" target="_blank">Screenshot your site now</a>' : '' );

	tj_add_image_html_custom( 'hmp_gallery_images', 'Add Gallery Images', $post_image_id, $image_ids, 'sortable', 'width=150&height=150&crop=1', $non_added_text );
}

function hmp_gallery_meta_box_submitted( $post ) {

	if ( isset( $_POST['hmp_gallery_images'] ) )
		update_post_meta( $post->ID, '_hmp_gallery_images', array_filter( explode( ',', $_POST['hmp_gallery_images'] ) ) );
}

function hmp_category_meta_box( $post ) {
	global $post;
	?>
	<p><label for="hmp_portfolio_category">Category</label></p>
	<select name="hmp_portfolio_category">
		<option value="">Select Category...</option>
		<?php
		$cats = get_terms('hmp-entry-category', array( 'hide_empty' => false ) );
		$obj_cat = wp_get_object_terms($post->ID, 'hmp-entry-category' );
		$obj_cat = $obj_cat[0];

		foreach( $cats as $cat ) : ?>
			<option <?php if($cat->term_id == $obj_cat->term_id) echo 'selected="selected" '; ?>value="<?php echo $cat->term_id ?>"><?php echo $cat->name ?></option>
		<?php
		endforeach;
		?>
	</select>
	<p><label for="hmp_portfolio_new_category">Add New Category</label></p>
	<input name="hmp_portfolio_new_category" type="text" />
	<?php
}
function hmp_category_meta_box_submitted( $post, $args = array() ) {

	if( $_POST['hmp_portfolio_new_category'] )
		wp_set_object_terms( $post->ID, (string) $_POST['hmp_portfolio_new_category'], 'hmp-entry-category' );
	elseif( $_POST['hmp_portfolio_category'] )
		wp_set_object_terms( $post->ID, (int) $_POST['hmp_portfolio_category'], 'hmp-entry-category' );
	elseif( $args['default'] ) {
		wp_set_object_terms( $post->ID, $args['default'], 'hmp-entry-category' );
	}
}

function hmp_additional_information_meta_box( $post ) {
	?>
	<p><label>URL</label></p>
	<input id="website_url" type="text" name="url" value="<?php echo hmp_get_url($post) ?>" />
	<p><label>Related Work (post ID)</label></p>
	<input type="text" name="related_work" value="<?php echo implode( ', ', (array) get_post_meta( $post->ID, '_related_work', true ) ); ?>" />
	<?php
}
function hmp_additional_information_meta_box_submitted( $post ) {

	if ( isset( $_POST['url'] ) )
		update_post_meta( $post->ID, '_url', esc_url($_POST['url']));

	if ( isset( $_POST['related_work'] ) ) :
		$related = explode( ',', esc_attr( $_POST['related_work'] ) );
		$related = array_map( 'absint', $related );
		update_post_meta( $post->ID, '_related_work', $related );
	endif;

} ?>