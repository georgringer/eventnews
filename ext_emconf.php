<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News events',
    'description' => 'Events for news',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => '',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '6.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.5.99',
            'news' => '11.1.0-11.99.9',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
