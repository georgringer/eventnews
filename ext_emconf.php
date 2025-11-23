<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'News events',
    'description' => 'Events for news',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => '',
    'state' => 'stable',
    'version' => '8.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.20-14.0.99',
            'news' => '13.0.0-14.99.9',
        ],
    ],
];
