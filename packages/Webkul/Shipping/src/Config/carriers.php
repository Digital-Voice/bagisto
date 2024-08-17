<?php

return [
    'flatrate' => [
        'code'         => 'flatrate',
        'title'        => 'Flat Rate',
        'description'  => 'Flat Rate Shipping',
        'active'       => true,
        'default_rate' => '10',
        'type'         => 'per_unit',
        'class'        => 'Webkul\Shipping\Carriers\FlatRate',
    ],

    'free' => [
        'code'         => 'free',
        'title'        => 'Free Shipping',
        'description'  => 'Free Shipping',
        'active'       => true,
        'default_rate' => '0',
        'class'        => 'Webkul\Shipping\Carriers\Free',
    ],

    'insidecity' => [
        'code'         => 'simple',
        'title'        => 'Inside Dhaka',
        'description'  => 'Per order 70 taka',
        // 'active'       => true,
        'default_rate' => '70',
        'type'         => 'per_order',
        'class'        => 'Webkul\Shipping\Carriers\InsideCity',
    ],

    'outsidecity' => [
        'code'         => 'simple',
        'title'        => 'Outside Dhaka',
        'description'  => 'Per order 130 taka',
        // 'active'       => true,
        'default_rate' => '130',
        'type'         => 'per_order',
        'class'        => 'Webkul\Shipping\Carriers\OutsideCity',
    ],
];
