<div class="<?php global $poll_id; echo is_passed($poll_id); ?>">
    <a href="<?php echo the_permalink(); ?>" class="interview__window-title"><?php the_title(); ?></a>
    <div id ="interview__button_<?php echo $poll_id; ?>" class="interview__window-button">
        <?php //вывод кнопок
            while ( have_rows('poll') ) : the_row();
                $variant_name = get_sub_field('variant');
                $format = "<div style='background:%s;'  class='button' data-poll_id=%s data-button_id=%s >%s</div>";
                printf($format, get_poll_color($variant_name, $poll_id), $poll_id, get_sub_field('variant'),  get_sub_field('variant'));  
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

                $koll = 0;

                while ( have_rows('poll') ) : the_row();
                    $variant_name = get_sub_field('variant');
                    $total = 0;

                    foreach ($count_array as $key => $value) {
                        $total += $value;
                    } 
                    if($count_array[$variant_name] != 0){ //если ответ никто не выбирал пропускаем рассчет и запишем 0%  
                        $percents = round(($count_array[$variant_name] / $total) * 100, 2) . "%";  
                        
                    }else{
                        $percents = "0%";
                    }

                    $form = "<div class='statistic__item__wrapper'><div id=%s style='width:%s; background:%s;' class='statistic__item' >%s-%s (%s)</div></div>";
                    printf($form, $percents, get_poll_color($variant_name, $poll_id), get_sub_field('variant'), get_sub_field('variant'), $percents);

                    $koll++;
                endwhile;

                echo "<pre>";
                print_r( $count_array);
                echo "</pre>";
                
                
            ?>
        </div>
        <div class="interview__window-total" id="total-statistic_<?php echo $poll_id; ?>"><p>всего проголосовало:<?php echo $total; ?><p></div>
    </div>
    <div data-id_poll="<?php echo $poll_id; ?>" class="interview__statistic-view interview__statistic-view_<?php echo $poll_id; ?>"></div>   
</div>       
<?php
    
?>
