<?php

return [
    'currency_class' => \BrandStudio\Currency\Currency::class,

    'enable_update' => false,
    'enable_create' => true,

    'use_backpack' => true,
    'crud_middleware' => false,//'role:admin|developer|manager',



    'url' => 'https://www.nationalbank.kz/rss/rates_all.xml',
];
