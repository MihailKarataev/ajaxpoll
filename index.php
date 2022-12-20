<?php get_header();?>

<?php

	$sinagogue_polls = get_posts( array('post_type'    => 'poll'));
	foreach( $sinagogue_polls as $post ){
        $poll_id = $post->ID;
        if( have_rows('poll') ): 
            $id_sum = 0;
            
?>          
            <?php 
                $set = 0;
                $var = "poll_" . $poll_id;
                if(isset($_COOKIE[$var])){//проверяем прошел ли пользователь опрос и скрываем кнопки
                    $set = "style='display:none;'";
                }else
            ?>
            <!-- перебираем данные--> 
            <div class="interview__wrapper">
                <div class="interview__modal__window">
                    <div class="interview__window-title"><?php the_title(); ?></div>
                    <div class="interview__window-button"<?php echo $set; ?>>
                        <?php 
                            while ( have_rows('poll') ) : the_row();
                                // отображаем вложенные поля
                                $colorname = get_sub_field('variant');
                                $format = "<div style='background:%s;' data-id_poll=%d data-id=%d class='button'>%s</div>";
                                printf($format, poll_get_color($colorname), $poll_id, $id_sum, get_sub_field('variant'));
                                $id_sum++;
                            endwhile;
                        ?>
                    </div>
                    <?php 
                        $var = "poll_" . $poll_id;
                        if(isset($_COOKIE[$var])){//проверяем проешл ли пользователь опрос и показываем статистику
                            $set = "style='display:block;'";
                        }else
                    ?>
                    <div class="interview__window-modal" <?php echo $set; ?>>
                        <div class="interview__window-statistisc">
                            <?php 
                                $id_sum= 0;
                                while ( have_rows('poll') ) : the_row();
                                    // отображаем статистику
                                    $colorname = get_sub_field('variant');
                                    $format = "<div style='width:%s; background:%s;' class='statistisc__item'>'%s' - %s</div>";
                                    printf($format, get_sub_field('percents'), poll_get_color($colorname),  get_sub_field('variant'), get_sub_field('percents'));
                                    $id_sum++;
                                endwhile;
                            ?>
                        </div>
                        <div class="interview__window-total" id="total-statistic"><p>всего проголосовало: <p></div>
                    </div>
                </div>     
            </div>
            <!--END перебираем данные--> 
        
<?php
    endif;
	}
	wp_reset_postdata();
?>

<?php get_footer();?>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script>
   $ = jQuery;
    $(document).ready(function(){
    $('.button').on('click', function(){
        let button_id = $(this).data('id'),
            poll_id = $(this).data('id_poll'); 
        $.ajax({
            url: '/wp-json/synagogues/v1/poll',
            type: 'POST',
            data: {
                button_id: button_id,
                poll_id: poll_id
            },
            success: function (data) {

                $('.interview__window-button').css("display","none");
                $('.interview__window-modal').css("display","block");
               

            }
        });
    });
});

</script>

