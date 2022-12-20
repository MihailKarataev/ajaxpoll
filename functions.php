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
    
    }



    function synagogues_poll ( WP_REST_Request $request ){//функция принимает и обрабатывает ajax запросы
        $button_id = $request->get_param('button_id');
        $poll_id = $request->get_param('poll_id');

        $post = get_field('poll_' . $button_id . '_number', $poll_id);//получаем колличество ответов этого варианта
        $post++;//почему то в функции не складывалось(
        update_field('poll_' . $button_id . '_number', $post, $poll_id);//записываем новый ответ
        setcookie('poll_'. $poll_id, 'voted', time() + (86400 * 30), "/");
        return new WP_REST_Response ( poll_write_statistic ($poll_id, $button_id), 200);
    }

    function poll_write_statistic ($poll_id, $button_id){// функция записывает статистику в поля
        $total=poll_get_amount($poll_id);
        $arr = get_field('poll', $poll_id, true);
        $button = 0;

        foreach ($arr as $key => $value) {
            $average = round(($value['number'] / $total) * 100, 1).'%';//получаю среднеарифметическое
            update_field('poll_' . $button . '_percents', $average, $poll_id);//записываю процентное соотношение
            $button++;
        }  

        return $total;
    }
    
    function poll_get_amount($poll_id){//функция возвращает общее колличество ответов на ворпос
        $arr = get_field('poll', $poll_id, true);
        $total = 0;

        foreach ($arr as $key => $value) {
            $total = $total + $value['number'];//почему то $total+$value['number']; не сработал
            
        }
    
        return $total;
    }

    function poll_get_color($name){//функция создает фон кодируя имя ответа и возвращая первые 6 символов
        return $backgroundColor = '#' . substr(md5($name), 0, 6);
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
        wp_enqueue_style( 'wp_enqueue_main_style', get_stylesheet_uri());
    }


?>