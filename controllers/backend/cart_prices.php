
<?php
/*****************************************************************************
*                                                                            *
*                   All rights reserved! eCom Labs LLC                       *
* http://www.ecom-labs.com/about-us/ecom-labs-modules-license-agreement.html *
*                                                                            *
*****************************************************************************/

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'edit') {
        $data = $_REQUEST['cart_prices_chart'];
        fn_ecl_spec_dev_update_cart_prices_chart($data);
    }

    if ($mode == 'update' || $mode == 'preview') {
        $category_id = $_REQUEST['updated_option']['category'];
        $group_id = $_REQUEST['updated_option']['group'];
        $status = $mode;
        
        $result = fn_ecl_spec_dev_update_prices($category_id, $group_id, $status);
        if (!empty($result)) {
            unset($_SESSION['result']);
            $_SESSION['result'] = $result;
        }
        
        return array(CONTROLLER_STATUS_OK, 'cart_prices.complete');
        Tygh::$app['view']->assign('result', $result);
    }

    return array(CONTROLLER_STATUS_OK, 'cart_prices.manage');
}

if ($mode == 'manage') {
    $chart = fn_ecl_spec_dev_get_cart_prices_chart();

    Tygh::$app['view']->assign('chart', $chart);  
}

if ($mode == 'edit') {
    $chart = fn_ecl_spec_dev_get_cart_prices_chart();
    
    Tygh::$app['view']->assign('chart', $chart);  
}

if ($mode == 'update') {
    $categories = db_get_hash_array("SELECT f.category_id, f.level, d.category FROM ?:categories as f RIGHT JOIN ?:category_descriptions as d ON d.category_id=f.category_id AND d.lang_code = ?s AND f.status = ?s AND f.product_count<>'' ORDER BY f.level", 'category_id', DESCR_SL, 'A');
    $groups = fn_ecl_spec_dev_get_groups();

    Tygh::$app['view']->assign('categories', $categories);
    Tygh::$app['view']->assign('groups', $groups);
}

if ($mode == 'complete') {

    if (!empty($_SESSION['result'])) {
        $result = $_SESSION['result'];
        unset($_SESSION['result']);
    }
    
    Tygh::$app['view']->assign('result', $result);
}