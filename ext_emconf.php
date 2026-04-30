<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News events',
    'description' => 'Events for news',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => '',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '7.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-14.3.99',
            'news' => '12.2.0-14.99.9',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
