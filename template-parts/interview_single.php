<?php 
    /*
 * Template name: Мой Супер-шаблон
 * Template post type: poll
 */
?>
<?php get_header();?>
<?php 
    $count_array = get_post_meta( get_the_ID(), 'count', true);
?>
<div class="container">
    <div class="diagram__wrapper">
        <div class="diagram__description">
            <p>Вопрос:</p><?php the_title(); ?>
        </div>
        <div class="diagram__radial">
            <div class="diagram__radial-title"></div>
            <div class="diagram__radial-round">
                <?php
                    foreach ($count_array as $key => $value) {
                        if($key == "set"){
                            continue;
                        }
                        echo "<div class='radial__item animate' style='--p:10;--c:lightgreen'> </div>";
                      
                    }
                ?>
            </div>
        </div>
        <div class="diagram__column"></div>
        <div class="diagram__statistic-table">
            <pre>
                <?php var_dump(get_post_meta( get_the_ID(), 'count', true));?>
            </pre>
        </div>
    </div>
</div>
<?php get_footer();?>