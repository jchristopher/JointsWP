<?php

// retrieve our search query if applicable
$query = isset( $_REQUEST['swpquery'] ) ? sanitize_text_field( $_REQUEST['swpquery'] ) : '';

// retrieve our pagination if applicable
$swppg = isset( $_REQUEST['swppg'] ) ? absint( $_REQUEST['swppg'] ) : 1;

// if a search was submitted, peform the search
if ( ! empty( $query ) && class_exists( 'SWP_Query' ) ) {

	$engine = 'test_engine'; // taken from the SearchWP settings screen

	$swp_query = new SWP_Query(
		// see all args at https://searchwp.com/docs/swp_query/
		array(
			's'      => $query,
			'engine' => $engine,
			'page'   => $swppg,
		)
	);

	// set up pagination
	$pagination = paginate_links( array(
		'format'  => '?swppg=%#%',
		'current' => $swppg,
		'total'   => $swp_query->max_num_pages,
	) );
}

get_header(); ?>

	<div id="content">

		<div id="inner-content" class="row">

		    <main id="main" class="large-8 medium-8 columns" role="main">

		    	<form role="search" method="get" class="search-form" action="">
					<label>
						<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'jointstheme' ) ?></span>
						<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder' ) ?>" value="<?php echo esc_attr( $query ); ?>" name="swpquery" title="<?php echo esc_attr_x( 'Search for:', 'jointstheme' ) ?>" />
					</label>
					<input type="submit" class="search-submit button" value="<?php echo esc_attr_x( 'Search', 'jointstheme' ) ?>" />
				</form>

		    	<header>
		    		<?php if ( ! empty( $query ) ) : ?>
		    			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyfifteen' ), $query ); ?></h1>
					<?php else : ?>
						<h1 class="page-title"><?php the_archive_title();?></h1>
						<?php the_archive_description('<div class="taxonomy-description">', '</div>');?>
					<?php endif; ?>
		    	</header>

		    	<?php if ( ! empty( $query )  && isset( $swp_query ) ) : ?>

		    		<?php
		    			// Search was performed, handle that
		    			if ( ! empty( $swp_query->posts ) ) {
		    				foreach ( $swp_query->posts as $post ) {
		    					setup_postdata( $post );
		    					get_template_part( 'parts/loop', 'archive' );
		    				}
		    				wp_reset_postdata();
		    			} else {
		    				// No search results found
		    				get_template_part( 'parts/content', 'missing' );
		    			}
		    		?>

		    	<?php else : ?>

		    		<?php /* Standard Archive page */ ?>

		    		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<!-- To see additional archive styles, visit the /parts directory -->
						<?php get_template_part( 'parts/loop', 'archive' ); ?>

					<?php endwhile; ?>

						<?php joints_page_navi(); ?>

					<?php else : ?>

						<?php get_template_part( 'parts/content', 'missing' ); ?>

					<?php endif; ?>

		    	<?php endif; ?>

			</main> <!-- end #main -->

			<?php get_sidebar(); ?>

	    </div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php get_footer(); ?>
