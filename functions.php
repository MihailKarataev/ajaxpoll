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
        ] );
    
    }

    function is_passed($poll_id){ //функция проверяет пройден ли тест и возвращает стиль 
        $var = "poll_" . $poll_id;
        if(isset($_COOKIE[$var])){
            $style = "interview__item-voted";
        }else{
            $style = "interview__item";
        }
        return $style;
    }

    function is_empty_count($button, $poll_id){ //проверяю на наличие и создаю массив колличества УТОЧНИТЬ ЭТУ КАШУ!!!!!!!!!!!!!
        $arr = get_post_meta( $poll_id, 'count', true);
        if($arr == ''){//если массив вообще пустой
            $arr = array($button => 0);
        }
        if(!isset($arr[$button])){
        $arr[$button] = 0;
        } 
        update_post_meta($poll_id, 'count', $arr);
        return $arr;
    }

    function synagogues_poll( WP_REST_Request $request ){//функция принимает и обрабатывает ajax запросы
        $button = $request->get_param('button_id');
        $poll_id = $request->get_param('poll_id');
        $count_array = get_post_meta( $poll_id, 'count', true);
        $count_array[$button]++;
        update_post_meta($poll_id, 'count', $count_array);
        //setcookie('poll_'. $poll_id, 'voted', time() + (86400 * 30), '/');
        return new WP_REST_Response ( $count_array, 200);//возвращаем массив измененых данных
    }

    function get_poll_color($param_name, $param_id){//функция создает фон кодируя имя ответа и возвращает первые 6 символов
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
        wp_enqueue_script( 'cookie-script', get_template_directory_uri() . '/js/js.cookie.min.js');
        wp_enqueue_script( 'interview-script', get_template_directory_uri() . '/js/main.js');
    }
    


?>