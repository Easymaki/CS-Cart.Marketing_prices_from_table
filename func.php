function fn_ecl_spec_dev_get_cart_prices_chart()
{
    $results = db_get_array("SELECT id, low_range, high_range, price FROM ?:cart_price_chart");
	
	return $results;
}

function fn_ecl_spec_dev_update_cart_prices_chart($data)
{
    $elm_ids[] = array();

    foreach($data as $id => $row) {
        if (!empty($id)) {
            $_data = array(
                'id' => $id,
                'low_range' => $row['low_range'],
                'high_range' => $row['high_range'],
                'price' => $row['price'],
            );
            db_query("REPLACE INTO ?:cart_price_chart ?e", $_data);
        }
            $elm_ids[] = $id;
    }

    $obsolete_elements_ids = db_get_fields("SELECT id FROM ?:cart_price_chart WHERE id NOT IN (?n)", $elm_ids);

    if (!empty($obsolete_elements_ids)) {
        db_query("DELETE FROM ?:cart_price_chart WHERE id IN (?n)", $obsolete_elements_ids);
    }
}

function fn_ecl_spec_dev_update_prices($category_id, $group_id)
{
    $chart = fn_ecl_spec_dev_get_cart_prices_chart();
    $products_ids[] = array();
    $category_ids[] = array();

    if (!empty($group_id)) {
		$category_ids = db_get_fields("SELECT category_id FROM ?:ecl_category_group_links WHERE group_id = ?i", $group_id);
    }

    if (!empty($category_id)) {
         array_push($category_ids, $category_id);
    }

    foreach ($category_ids as $category_id) {
        $products_ids = db_get_fields("SELECT f.product_id FROM ?:products_categories as f
        LEFT JOIN ?:products as d ON d.product_id=f.product_id AND f.category_id = ?i WHERE d.update_price = ?s", $category_id, 'Y');
    }

    if (!empty($products_ids)) {
        foreach ($products_ids as $product_id) {
            $product_prices = db_get_row("SELECT price, foil_price FROM ?:product_prices WHERE product_id = ?i", $product_id);
            if (!empty($product_prices)) {
                $price = $product_prices['price'];
                $foil_price = $product_prices['foil_price'];
                
                $updated_price = fn_ecl_spec_dev_get_price_from_chart($chart, $price);
                $updated_foil_price = fn_ecl_spec_dev_get_price_from_chart($chart, $foil_price);

                $_data = array(
						'price' => $updated_price,
						'foil_price' => $updated_foil_price,	
					);

                if (!empty($_data)) {
                    db_query("UPDATE ?:product_prices SET ?u WHERE product_id = ?i", $_data, $product_id);
                }
            }
        }
    }
}   

function fn_ecl_spec_dev_get_groups()
{
    $groups = db_get_array("SELECT * FROM ?:ecl_category_groups");
    
    return $groups;
}

function fn_ecl_spec_dev_get_price_from_chart($chart, $cprice)
{

    foreach ($chart as $row) {
        if ($cprice >= $row['low_range'] && $cprice <= $row['high_range']) {
            $result_price = $row['price'];
        }

        if ($cprice <= 0.24) {
            $result_price = 0.24;
        }

        if (empty($result_price)) {
            $result_price = $cprice;
        }

    }

    return $result_price;
}
