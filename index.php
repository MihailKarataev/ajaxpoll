<?php get_header();?>

<?php
	$sinagogue_polls = get_posts( array('post_type'    => 'poll'));
	foreach( $sinagogue_polls as $post ){
        $poll_id = $post->ID;
        if( have_rows('poll') ){
            get_template_part('template-parts/interview_frame');
        } 
	}
	wp_reset_postdata();
?>

<?php get_footer();?>

