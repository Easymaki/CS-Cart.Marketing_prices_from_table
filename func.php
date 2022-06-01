<?php


function fn_get_cart_prices_chart()
{
    $results = db_get_array("SELECT id, low_range, high_range, price FROM ?:cart_price_chart");
	
	return $results;
}

function fn_update_cart_prices_chart($data)
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

function fn_update_prices($category_id, $group_id, $status)
{
    $result = array(
		'products' => array(),
        'file' => ''
	);
    
    $chart = fn_get_cart_prices_chart();

    $products_ids[] = array();

    if (!empty($category_id) || (!empty($group_id))) {
        $category_ids[] = array();
    }

    if (!empty($group_id)) {
		$category_ids = db_get_fields("SELECT category_id FROM ?:ecl_category_group_links WHERE group_id = ?i", $group_id);
    }

    if (!empty($category_id)) {
        array_push($category_ids, $category_id);
    }

    if (!empty($category_ids)) {
        foreach ($category_ids as $category_id) {
            $products_ids = db_get_fields("SELECT f.product_id FROM ?:products_categories as f
            LEFT JOIN ?:products as d ON d.product_id=f.product_id AND f.category_id = ?i WHERE d.update_price = ?s", $category_id, 'Y');
        }
    } else {
        $products_ids = db_get_fields("SELECT product_id FROM ?:products WHERE update_price = ?s", 'Y');
    }

    if (!empty($products_ids)) {
        foreach ($products_ids as $product_id) {
            $product_prices = db_get_row("SELECT price, foil_price FROM ?:product_prices WHERE product_id = ?i", $product_id);
            $rarity = db_get_field("SELECT rariry FROM ?:products WHERE product_id = ?i", $product_id);
            if (!empty($product_prices)) {
                $price = $product_prices['price'];
                $foil_price = $product_prices['foil_price'];
                
                $updated_price = fn_get_price_from_chart($chart, $price, $rarity);
                $updated_foil_price = fn_get_price_from_chart($chart, $foil_price, $rarity);

                if (($price != $updated_price) || ($foil_price != $updated_foil_price)) {

                    $product_data = db_get_row("SELECT f.product, d.amount FROM ?:product_descriptions as f LEFT JOIN ?:products as d ON f.product_id = d.product_id WHERE f.product_id = ?i", $product_id);

                    $result['products'][$product_id] = array(
                        'card_name' => $product_data['product'],
                        'rarity' =>  $rarity,
                        'stock' => $product_data['amount'],
                        'old_price' => $price,
                        'new_price' => $updated_price,
                        'old_foil_price' => $foil_price,
                        'updated_foil_price' => $updated_foil_price
				    );

                    // update data into db
                    if ($status == 'update') {
                        $_data = array(
                                'price' => $updated_price,
                                'foil_price' => $updated_foil_price,	
                            );

                        if (!empty($_data)) {
                            db_query("UPDATE ?:product_prices SET ?u WHERE product_id = ?i", $_data, $product_id);
			}
	    	}
                    // write data to file
		$dir = fn_get_public_files_path() . "ecl_cart_prices/";
		$file_name = 'product_update_results_'.date("Ymd_His").'.csv';
		fn_rm($dir);
		fn_mkdir($dir);
		$file_path = $dir . $file_name;
		$file = fopen( $file_path, 'w' );
			
		if ($file) {
			fputcsv($file, array('Card Name', 'Rarity', 'Stock', 'Old Price', 'New Price', 'Old Foil Price', 'New Foil Price'));
			
			foreach($result['products'] as $line) {
				fputcsv($file, $line);
			}
			
			fclose($file);
			$result['file'] = fn_get_rel_dir($file_path);
    		}	
	    }
	}
    }
    return $result;
}

function fn_get_groups()
{
    $groups = db_get_array("SELECT * FROM ?:category_groups");
    
    return $groups;
}

function fn_get_price_from_chart($chart, $cprice, $rarity)
{
    foreach ($chart as $row) {

        if ($cprice >= $row['low_range'] && $cprice <= $row['high_range']) {
            $result_price = $row['price'];
        } elseif ($cprice <= 0.24 && ($rarity == 'C' || $rarity == 'Common' || $rarity == 'common')) {
				$result_price = 0.24;
        } elseif ($cprice <= 0.98 && ($rarity == 'U' || $rarity == 'Uncommon' || $rarity == 'uncommon')) {
            $result_price = 0.98;
        } elseif ($cprice <= 1.48 && ($rarity == 'R' || $rarity == 'Rare' || $rarity == 'rare')) {
            $result_price = 1.48;
        } elseif ($cprice <= 2.48 && ($rarity == 'M' || $rarity == 'Mythic' || $rarity == 'mythic')) {
            $result_price = 2.48;
        } elseif (empty($result_price)) {
                $result_price = $cprice;
        }

    }
    return $result_price;
}
