<?php get_header();?>

<?php
	$sinagogue_polls = get_posts( array('post_type'    => 'poll'));
	foreach( $sinagogue_polls as $post ){
        $poll_id = $post->ID;
        if( have_rows('poll') ):          
?>          
            <!-- перебираем данные --> 
            <?php 
                $var = "poll_" . $poll_id;
                if(isset($_COOKIE[$var])){
                    $style = "interview__item-voted";
                }else{
                    $style = "interview__item";
                }
            ?>
            <div class="<?php echo $style; ?>">
                <a href="<?php echo the_permalink(); ?>" class="interview__window-title"><?php the_title(); ?></a>
                <div id ="interview__button<?php echo $poll_id; ?>" class="interview__window-button">
                    <?php 
                        while ( have_rows('poll') ) : the_row();
                            $variant_name = get_sub_field('variant');
                            $format = "<div style='background:%s;' data-id_poll=%d data-id=%s class='button'>%s</div>";
                            printf($format, poll_get_color($variant_name, $poll_id), $poll_id, get_sub_field('variant'),  get_sub_field('variant'));  
                        endwhile;
                    ?>
                </div>
                <div  class="interview__statistic" id="interview__statistic<?php echo $poll_id;?>" style="display:none;">
                    <div class="interview__window-statistic">
                        <?php 
                            $kol = 0;
                            while ( have_rows('poll') ) : the_row();
                                    $kol++;
                            endwhile;
                            $x = get_post_meta( $post_id, 'count', true );
                            print_r($x);
                            $variant_name = get_sub_field('variant');
                            $count_array = is_empty_count($x, $kol, $variant_name);
                            while ( have_rows('poll') ) : the_row();
                                $format = "<div class='statistic__item__wrapper' data-variant=%s><div style='width:%s; background:%s;' class='statistic__item' id=%s%s>'%s' - %s</div></div>";
                                printf($format, $kol, get_sub_field('percents'), poll_get_color($variant_name, $poll_id), get_sub_field('variant'), $poll_id, get_sub_field('variant'), $count_array[$variant_name]);
                                var_dump($count_array);
                                $kol++;
                            endwhile;
                        ?>
                    </div>
                    <div class="interview__window-total" id="total-statistic<?php echo $poll_id; ?>"><p>всего проголосовало: <p></div>
                </div>
                <div data-id_poll="<?php echo $poll_id; ?>" class="interview__statistic-view interview__statistic-view<?php echo $poll_id; ?>"></div>   
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

        $('.interview__statistic-view').on('click', function(){
            let poll_id = $(this).data('id_poll');
            var button_status = document.getElementById("interview__statistic"+poll_id);
            if (button_status.style.display === "none") {
                $('#interview__statistic'+poll_id).slideDown();
                $('.interview__statistic-view'+poll_id).css("transform", "rotate(180deg)");
            } else {
                $('#interview__statistic'+poll_id).slideUp();
                $('.interview__statistic-view'+poll_id).css("transform", "rotate(0deg)");
            }       
        });
    
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
                    $('#interview__button'+poll_id).css("display", "none");
                    $('#interview__statistic'+poll_id).slideDown();
                    $('.interview__statistic-view'+poll_id).css("transform", "rotate(180deg)");
                    $('.interview__statistic-view'+poll_id).css("transform", "rotate(180deg)");
                    let total = 0;
                    data.forEach(function callback(currentValue, index, data) {
                        $('#'+currentValue['value']+poll_id).css("width", currentValue['percents']);
                        $('#'+currentValue['value']+poll_id).html(currentValue['percents']);
                        total += parseInt(currentValue['sum']);
                        console.log(currentValue);
                    });
                    $('#total-statistic'+poll_id).html(total+" человек");
                }
            });
        });
    });
</script>