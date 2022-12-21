<!-- перебираем данные--> 
<div class="interview__item">
    <div class="interview__window-title"><?php the_title(); ?></div>
    <div id ="interview__button" class="interview__window-button">
        <?php 
            while ( have_rows('poll') ) : the_row();
                // отображаем вложенные поля
                $variant_name = get_sub_field('variant');
                $format = "<div style='background:%s;' data-id_poll=%d data-id=%d class='button'>%s</div>";
                printf($format, poll_get_color($variant_name, $poll_id), $poll_id, $id_sum, get_sub_field('variant'));
                $id_sum++;
            endwhile;
        ?>
    </div>
    <div id ="interview__statistic<" class="interview__statistic">
        <div class="interview__window-statistic">
            <?php 
                $id_sum= 0;
                while ( have_rows('poll') ) : the_row();
                    // отображаем статистику
                    $variant_name = get_sub_field('variant');
                    $format = "<div style='width:%s; background:%s;' class='statistic__item' id=%s%s>'%s' - %s</div>";
                    printf($format, get_sub_field('percents'), poll_get_color($variant_name, $poll_id), get_sub_field('variant'), $poll_id, get_sub_field('variant'), get_sub_field('percents'));
                    $id_sum++;
                endwhile;
            ?>
        </div>
        <div class="interview__window-total" id="total-statistic"><p>всего проголосовало: <p></div>
    </div>
    <div class="interview__statistic-view interview__statistic-view"></div>   
</div>
<!--END перебираем данные--> 
