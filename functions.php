<?php
    add_action( 'init', 'register_post_types' );

    function register_post_types(){
    
        register_post_type( 'poll', [
            'label'  => null,
            'labels' => [
                'name'               => 'poll', // основное название для типа записи
                'singular_name'      => 'Опрос', // название для одной записи этого типа
                'add_new'            => 'Добавить опрос', // для добавления новой записи
                'add_new_item'       => 'Добавление опроса', // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => 'Редактирование опроса', // для редактирования типа записи
                'new_item'           => 'Новый опрос', // текст новой записи
                'view_item'          => 'Смотреть опрос', // для просмотра записи этого типа.
                'search_items'       => 'Искать опрос', // для поиска по этим типам записи
                'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
                'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
                'menu_name'          => 'Опросы сайта', // название меню
            ],
            'description'            => '',
            'public'                 => true,
            // 'publicly_queryable'  => null, // зависит от public
            // 'exclude_from_search' => null, // зависит от public
            // 'show_ui'             => null, // зависит от public
            // 'show_in_nav_menus'   => null, // зависит от public
            'show_in_menu'           => null, // показывать ли в меню админки
            // 'show_in_admin_bar'   => null, // зависит от show_in_menu
            'show_in_rest'        => null, // добавить в REST API. C WP 4.7
            'rest_base'           => null, // $post_type. C WP 4.7
            'menu_position'       => null,
            'menu_icon'           => null,
            //'capability_type'   => 'post',
            //'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
            //'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
            'hierarchical'        => false,
            'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
            'taxonomies'          => [],
            'has_archive'         => false,
            'rewrite'             => true,
            'query_var'           => true,
        ] );
            
        //мета колличества
        register_meta( 'poll', 'count', [
            'object_subtype'    => 'poll', 
            'show_in_rest'      => true,
            'sanitize_callback' => 'absint',
        ] );
        //мета процента
        register_meta( 'poll', 'percents', [
            'object_subtype'    => 'poll',
            'show_in_rest'      => true,
            'sanitize_callback' => 'absint',
        ] );
    
    }



    function is_empty_count($arr, $poll_id, $button){ //проверяю на наличие и создаю массив колличества
         if(!isset($arr)){
             for ($i=0; $i < $poll_id; $i++) { 
                 $arr []= array($button =>  0);
             }
         }
         return $arr;
    }
        



    function synagogues_poll( WP_REST_Request $request ){//функция принимает и обрабатывает ajax запросы
        $button = $request->get_param('button_id');
        $poll_id = $request->get_param('poll_id');
        $count_array = get_post_meta( $post_id, 'count', true );
        if(isset($count_array['$button'])){
            $post++;
        }else{
            $post=1;
        }
        
        //почему то в функции не складывалось(
        update_post_meta( $poll_id, 'percents', $post);//записываем новый ответ
        
        setcookie('poll_'. $poll_id, 'voted', time() + (86400 * 30), '/');
        $change = poll_write_statistic ($poll_id, $button_id);
        return new WP_REST_Response ( $change, 200);//возвращаем массив измененых данных
    }

    function poll_write_statistic($poll_id, $button_id){// функция записывает статистику в поля
        $total=poll_get_amount($poll_id);
        $arr = get_field('poll', $poll_id, true);
        $button = 0;
        foreach ($arr as $key => $value) {
            if(!isset($value['number'])){
                $value['number'] = 0;
            }
            $average = round(($value['number'] / $total) * 100, 1).'%';//получаю среднеарифметическое
            update_field('poll_' . $button . '_percents', $average, $poll_id);//записываю процентное соотношение
            $settings[] = array(
                'percents'   => $average,
                'value'     => $value['variant'],
                'sum'       => $value['number'],
            );
            $button++;
        }  
        return $settings;
    }
    
    function poll_get_amount($poll_id){//функция возвращает общее колличество ответов на ворпос
        $arr = get_field('poll', $poll_id, true);
        $total = 0;
        foreach ($arr as $key => $value) {
            $total = $total + $value['number'];//почему то $total+$value['number']; не сработал
        }
        return $total;
    }

    function poll_get_color($param_name, $param_id){//функция создает фон кодируя имя ответа и возвращает первые 6 символов
        return $backgroundColor = '#' . substr(md5($param_id), 0, 3) . substr(md5($param_name), 0, 3);
    }
    add_action('rest_api_init', function(){
        register_rest_route( 'synagogues/v1/', '/poll', [
                'methods' => 'POST',
                'callback' => 'synagogues_poll',
        ]);
    });
    
    //ПОДКЛЮЧАЮ СТИЛИ
    add_action( 'wp_enqueue_scripts', 'wp_enqueue_main_style');
    function wp_enqueue_main_style() {
        wp_enqueue_style( 'main_style', get_stylesheet_uri());
    }
    
    add_action( 'wp_enqueue_scripts', 'my_scripts_method' );
    function my_scripts_method(){
        wp_enqueue_script( 'newscript', get_template_directory_uri() . '/js/js.cookie.min.js');
    }
    


?>