<div class="<?php global $poll_id; echo is_passed($poll_id); ?>">
    <a href="<?php the_permalink(); ?>" class="interview__window-title"><?php the_title(); ?></a>
    <div id ="interview__button_<?php echo $poll_id; ?>" class="interview__window-button">
        <?php //вывод кнопок
            while ( have_rows('poll') ) : the_row();
                $variant_name = get_sub_field('variant');
                $format = "<div style='background:%s;'  class='button' data-poll_id=%s data-button_id=%s >%s</div>";
                printf($format, '#378b38', $poll_id, get_sub_field('variant'),  get_sub_field('variant'));  
            endwhile;
        ?>
        
    </div>
    <div  class="interview__statistic" id="interview__statistic_<?php echo $poll_id;?>" style="display:none;">
        <div class="interview__window-statistic">
            <?php //вывод статистики
            
                while ( have_rows('poll') ) : the_row();
                    //тут создается массив с ответами
                    $count_array = is_empty_count(get_sub_field('variant'), $poll_id);
                endwhile;

                while( have_rows('poll') ) : the_row();
                    $variant_name = get_sub_field('variant');
                    $total = 0;
                    foreach($count_array as $key => $value) {
                        if(is_array($value)){
                            continue;
                        }
                        $total += $value;
                    } 
                    $count_arr = get_post_meta( $poll_id, 'count', true);
                    $count_arr['set']['total'] = $total; 
                    
                    if($count_array[$variant_name] !== 0){ //если ответ никто не выбирал пропускаем рассчет и запишем 0%  
                        $percents = round(($count_array[$variant_name] / $total) * 100, 2) . "%";
                        
                    }else{
                        $percents = "0%";
                    }
                    update_post_meta($poll_id, 'count', $count_arr);
                    $form = "<div class='statistic__item__wrapper'><div id=%s_%s style='width:%s; background:%s;' class='statistic__item' >%s-%s (%s)</div></div>";
                    printf($form, $poll_id, get_sub_field('variant'), $percents, '#378b38', get_sub_field('variant'), $percents , $count_array[$variant_name]);
                endwhile;
            ?>
        </div>
        <div class="interview__window-total" id="total-statistic_<?php echo $poll_id; ?>"><p>всего проголосовало:<?php echo $total; ?><p></div>
    </div>
    <div data-poll_id="<?php echo $poll_id; ?>" class="interview__statistic-view interview__statistic-view_<?php echo $poll_id; ?>"></div>   
</div>       
