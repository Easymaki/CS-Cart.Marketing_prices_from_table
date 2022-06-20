<?php

use Tygh\Registry;
use Tygh\Settings;

$schema = [
    'section'     => 'cart_prices_chart',
    'name'        => __('cart_price_chart'),
    'pattern_id'  => 'cart_prices_chart',
    'key'         => ['id'],
    'table'       => 'cart_price_chart',

    'ID' => [
        'db_field' => 'id',
    ],

    'Low range' => [
        'db_field' => 'low_range',
    ],

    'High range' => [
        'db_field' => 'high_range',
    ],

    'Price' => [
        'db_field' => 'price',
    ],

    'export_fields' => [
        'ID' => [
            'db_field' => 'id',
            'alt_key' => true,
        ],
        'Low range' => [
            'db_field'  => 'low_range',
            'required'  => true,
        ],
        'High range' => [
            'db_field'     => 'high_range',
            'required'  => true,
        ],
        'Price' => [
            'db_field' => 'price',
            'required' => true,
        ]
    ],
];
return $schema;
