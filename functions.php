<?php

add_filter('wp_get_nav_menu_items', 'prefix_add_categories_to_menu', 10, 3);

function prefix_add_categories_to_menu($items, $menu, $args) {
	// Display only this categories
    $showcat_array = array(355,356,358,359,360,366,369);
    // Make sure we only run the code on the applicable menu "add slug"
     if($menu->slug !== 'main') return $items;

    // Get all the product categories
    $categories = get_categories(array(
        'taxonomy' => 'product_cat',
        'orderby' => 'name',
        'show_count' => 0,
        'pad_counts' => 0,
        'hierarchical' => 1,
        'hide_empty' => 0,
        'depth' => $depth,
        'title_li' => '',
        'echo' => 0 
    ));

    $menu_items = array();
    // Create menu items
    foreach($categories as $category) {
       
        $new_item = (object)array(
            'ID' => intval($category->term_id),
            'db_id' => intval($category->term_id),
            'menu_item_parent' => intval($category->category_parent),
            'post_type' => "nav_menu_item",
            'object_id' => intval($category->term_id),
            'object' => "product_cat",
            'type' => "taxonomy",
            'type_label' => __("Product Category", "textdomain"),
            'title' => $category->name,
            'url' => get_term_link($category),
            'classes' => array()
        );

        if(in_array($category->parent,$showcat_array) || in_array($category->term_id,$showcat_array)){
          array_push($menu_items, $new_item);
          /* Merge menu items*/
          foreach($items as $item) {
            array_push($menu_items, $item);
          }
        }
      
    }
    $menu_order = 0;
    // Set the order property
    foreach ($menu_items as &$menu_item) {
        $menu_order++;
        $menu_item->menu_order = $menu_order;
    }
    unset($menu_item);
    //$menu_items = array_merge($menu_items, $items);
    return $menu_items;
}
