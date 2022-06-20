
<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'edit') {
        $data = $_REQUEST['cart_prices_chart'];
        fn_update_cart_prices_chart($data);
    }

    if ($mode == 'update') {
        $category_id = $_REQUEST['updated_option']['category'];
        $group_id = $_REQUEST['updated_option']['group'];
        
        if (!empty($category_id) || (!empty($group_id))) {
            fn_update_prices($category_id, $group_id);
        }
    }
    return array(CONTROLLER_STATUS_OK, 'cart_prices.manage');
}

if ($mode == 'manage') || ($mode == 'edit') {
    $chart = fn_get_cart_prices_chart();

    Tygh::$app['view']->assign('chart', $chart);  
}

if ($mode == 'update') {
    $lang = (DESCR_SL);

    $categories = db_get_hash_array("SELECT f.category_id, f.level, d.category FROM ?:categories as f RIGHT JOIN ?:category_descriptions as d ON d.category_id=f.category_id AND d.lang_code = ?s AND f.status = ?s AND f.product_count<>'' ORDER BY f.level", 'category_id', $lang, 'A');
    $groups = fn_get_groups();

    Tygh::$app['view']->assign('categories', $categories);
    Tygh::$app['view']->assign('groups', $groups);
}
